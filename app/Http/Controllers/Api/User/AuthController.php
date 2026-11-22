<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Otp;
use App\Models\Pin;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use App\Services\OtpService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Notifications\PasswordResetNotification;
use App\Notifications\ThankYouNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

use App\Models\Wallet;

class AuthController extends Controller
{
    /**
- Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
- Show the form for creating a new resource.
     */
     
    public function create(Request $request, OtpService $otpService)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|regex:/^[0-9]+$/|unique:users,phone',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'image' => 'required|file|mimes:jpg,jpeg,png,webp',
            ]);
    
            if ($validatedData->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validatedData->errors()
                ], 422);
            }
    
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('profile', 'public');
            }
    
            // ✅ User create with hashed password
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'image' => $imagePath ? 'storage/' . $imagePath : null,
            ]);
    
            // ✅ Create wallet
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
            ]);
    
            // ✅ Generate OTP
            $otp = $this->generateOtp();
    
            // ✅ Save OTP to database
            Otp::create([
                'otp' => $otp,
                'user_id' => $user->id,
                'expires_at' => now()->addMinutes(10),
            ]);

            try {
                // ✅ Send OTP via service
                // $otpService->sendOtp($request->email, $request->phone, $otp);
    
                // ✅ Send Thank You Email Notification
                $user->notify(new ThankYouNotification($user));
    
            } catch (\Exception $e) {
                Log::warning('Mail/OTP failed during register: ' . $e->getMessage());
            }
    
            return response()->json([
                'status' => true,
                'message' => 'User registered successfully! A Thank You email has been sent.',
                'data' => $user
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Unexpected error: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
- Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|unique:users|regex:/^[0-9]+$/',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create($validatedData);
    }

    public function login(Request $request)
    {
        try {
            // 🧩 Step 1: Validation
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255',
                'password' => 'required|min:5|max:255',
            ]);
    
            if ($validator->fails()) {
                Log::error('❌ Validation failed: ', $validator->errors()->toArray());
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed. Please check your input.',
                    'errors' => $validator->errors(),
                ], 422);
            }
    
            // 🧩 Step 2: Check if user exists
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                Log::warning('❌ Email not found: ' . $request->email);
                return response()->json([
                    'status' => false,
                    'message' => 'This email is not registered. Please sign up first.',
                ], 404);
            }
    
            // 🧩 Step 3: Check password manually
            if (!Hash::check($request->password, $user->password)) {
                Log::warning('❌ Invalid password for user: ' . $request->email);
                return response()->json([
                    'status' => false,
                    'message' => 'Incorrect password. Please try again.',
                ], 401);
            }
    
            // 🧩 Step 4: Attempt authentication
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                Log::info('✅ Auth attempt successful for: ' . $request->email);
    
                $user = Auth::user();
                $token = $user->createToken('AuthToken')->plainTextToken;
    
                // 🧩 Step 5: Fetch related data
                $userData = User::where('email', $request->email)
                    ->with(
                        'wallet',
                        'document',
                        'nomineedetail',
                        'bankdetail',
                        'occupationdetail',
                        'currentaddress',
                        'Mychit.chitgroup.chit.chitdetail',
                        'pin'
                    )
                    ->first();
    
                if (!$userData) {
                    Log::error('❌ User data not found after Auth for: ' . $request->email);
                    return response()->json([
                        'status' => false,
                        'message' => 'User data not found. Please contact support.',
                    ], 404);
                }
    
                Log::info('✅ User data fetched successfully for: ' . $request->email);
    
                return response()->json([
                    'status' => true,
                    'message' => 'Login successful.',
                    'token' => $token,
                    'user' => $userData,
                    'profile_status' => [                                    // ✅ Added
                        'address'    => $this->state($userData->currentaddress),
                        'nominee'    => $this->state($userData->nomineedetail),
                        'bank'       => $this->state($userData->bankdetail),
                        'documents'  => $this->docState($userData),
                        'education'  => $this->state($userData->educationdetail ?? null),
                        'occupation' => $this->state($userData->occupationdetail),
                    ],
                ], 200);
            }
    
            Log::error('❌ Auth attempt failed for email: ' . $request->email);
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials. Please try again.',
            ], 401);
    
        } catch (\Exception $e) {
            Log::error('🔥 Unhandled Exception in login: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Server error. Please try again later.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        
        $user = User::where('email', $request->email)->first();
    
        // Delete any existing reset tokens for the user
        \DB::table('password_reset_tokens')->where('email', $user->email)->delete();
    
        $token = Str::random(60);
        
        try {
            \DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => now(),
            ]);
    
            $user->notify(new PasswordResetNotification($token, $user->email));
    
            return response()->json(['message' => 'Password reset email sent successfully.'], 200);
        } catch (\Exception $e) {
            // Log::error('Failed to send password reset email: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to send email. Please try again.'], 500);
        }
    }

    public function showResetForm(Request $request)
    {
        $data = $request->all();
        // print_r($data);
        // die();
        
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
        ]);

        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$tokenData) {
            return redirect('/login')->withErrors(['error' => 'Invalid token or email']);
        }
        return view('auth.passwords.reset', [
            'token' => $request->token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        // Fetch token from DB
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();
    
        if (!$tokenData) {
            return response()->json(['error' => 'Invalid token or email'], 400);
        }
    
        // Check token expiry (1 hour validity)
        $tokenCreatedAt = new Carbon($tokenData->created_at);
        $expiryTime = $tokenCreatedAt->addHour(); 
    
        if (Carbon::now()->greaterThan($expiryTime)) {
            return response()->json(['error' => 'This password reset token has expired.'], 400);
        }
    
        // Find user
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        // ✅ Hash password before saving
        $user->password = Hash::make($request->password);
        $user->save();
    
        // Delete used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
    
        return response()->json(['message' => 'Password has been successfully updated.'], 200);
    }

    public function loginWithOtp(Request $request)
    {
    }

    /**
- Social Login (Google / Apple)
     */
    public function socialLogin(Request $request)
    {
        $request->validate([
            'provider' => 'required|in:google,apple',
            'id_token' => 'required|string',
        ]);

        $provider = $request->provider;
        $idToken = $request->id_token;

        if ($provider === 'google') {
            $response = Http::get("https://oauth2.googleapis.com/tokeninfo", [
                'id_token' => $idToken
            ]);

            if ($response->failed()) {
                return response()->json(['status' => false, 'message' => 'Invalid Google Token'], 401);
            }

            $googleData = $response->json();
            $sub = $googleData['sub'] ?? null;
            $email = $googleData['email'] ?? null;
            $name = $googleData['name'] ?? ($request->name ?? 'User');

            if (!$sub || !$email) {
                return response()->json(['status' => false, 'message' => 'Failed to fetch user info from Google'], 400);
            }
        } else {
            // Apple Login
            $sub = $request->input('id');
            $email = $request->input('email');
            $name = $request->input('name', 'Apple User');

            if (!$sub) {
                return response()->json(['status' => false, 'message' => 'Invalid Apple Token'], 401);
            }
        }

        $col = $provider . '_id';
        $user = User::where($col, $sub)->orWhere('email', $email)->first();
        $existing = (bool) $user;

        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::random(40)),
                $col => $sub,
            ]);
        } elseif (!$user->$col) {
            $user->update([$col => $sub]);
        }

        $pinRecord = Pin::where('user_id', $user->id)->first();
        $hasPin = $pinRecord && !empty($pinRecord->pin) && ($pinRecord->is_varify ?? false);

        $token = $user->createToken('app')->plainTextToken;

        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => $user,
            'is_existing_user' => $existing,
            'has_pin' => $hasPin,
        ], 200);
    }

    /**
- Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
- Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
- Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
- Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function generateOtp()
    {
        return rand(100000, 999999); 
    }

    // ✅ Helper function for profile status
    private function state($rec)
    {
        $r = $rec instanceof \Illuminate\Support\Collection ? $rec->first() : $rec;
        if (!$r) return 'missing';

        return match (strtolower($r->status ?? 'approved')) {
            'approved', 'verified' => 'completed',
            'rejected' => 'rejected',
            default => 'pending',
        };
    }

    // ✅ Helper function for documents status
    private function docState($user)
    {
        $doc = $user->document instanceof \Illuminate\Support\Collection ? $user->document->first() : $user->document;
        
        // Agar documents record ya profile image missing hai
        if (!$doc || empty($user->image)) {
            return 'missing';
        }

        $status = strtolower($doc->status ?? 'approved');
        if ($status === 'rejected') return 'rejected';
        if ($status === 'pending') return 'pending';

        return 'completed';
    }
}

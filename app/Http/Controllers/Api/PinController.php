<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Pin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PinOtp;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
class PinController extends Controller
{
    public function index()
    {
        $pins = Pin::all();
        return response()->json(['pin' => $pins], 200);
    }

    public function create()
    {
        return view('pins.create');
    }

    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'pin' => 'required|string|min:4|max:4',
        ]);
    
        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }
    
        $pin = Pin::where('user_id', $request->user_id)->first();
    
        if (!$pin) {
            return response()->json(['error' => 'User pin not found'], 404);
        }
    
        if ($pin->pin == $request->pin && $pin->is_varify == true) {
            return response()->json(['pin' => $pin, 'status' => 'success'], 200);
        }
    
        return response()->json(['error' => 'Invalid PIN'], 404);
    }

  public function verifyPin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'pin' => 'required|string',
        ]);

        // ✅ Use Eloquent model so encrypted PINs are automatically decrypted
        $pinRecord = Pin::where('user_id', $request->user_id)->first();

        if (!$pinRecord) {
            return response()->json([
                'status' => false,
                'message' => 'PIN not found',
            ]);
        }

        $storedPin = $pinRecord->getRawOriginal('pin'); // get encrypted/bcrypt/plain value directly

        $isMatch = false;

        try {
            if (strpos($storedPin, '$2y$') === 0) {
                // bcrypt-hashed PIN
                $isMatch = Hash::check($request->pin, $storedPin);
            } else {
                // Try decrypting (for Crypt::encryptString())
                try {
                    $decrypted = Crypt::decryptString($storedPin);
                    $isMatch = $request->pin === $decrypted;
                } catch (\Exception $e) {
                    // fallback to plain text match
                    $isMatch = $request->pin === $storedPin;
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error verifying PIN',
                'error' => $e->getMessage(),
            ]);
        }

        if ($isMatch) {
            return response()->json([
                'status' => true,
                'message' => 'PIN verified successfully',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Incorrect PIN',
        ]);
    }



           public function sendOtpEmail(Request $request)
        {
            // Validate request data
            $validatedData = Validator::make($request->all(), [
                'user_id' => [
                    'required',
                    'integer',
                    Rule::exists('users', 'id'),
                ],
            ]);
        
            if ($validatedData->fails()) {
                return response()->json(['errors' => $validatedData->errors()], 422);
            }
        
            // Find or create Pin record
            $pin = Pin::where('user_id', $request->user_id)->first();
        
            if (!$pin) {
                $pin = new Pin();
                $pin->user_id = $request->user_id;
                $pin->pin = $request->pin ?? null; // optional if you send pin
                $pin->save();
            }
        
            // Find user
            $user = User::find($request->user_id);
        
            // Generate OTP
            $otp = $this->generateOtp();
        
            // Send OTP email
            Mail::to($user->email)->send(new SendOtpMail($otp));
        
            // Find or create pin_otp record
            $pinOtp = PinOtp::where('pin_id', $pin->id)->first();
        
            if ($pinOtp) {
                $pinOtp->otp = $otp;
                $pinOtp->expires_at = Carbon::now()->addSeconds(30);
                $pinOtp->save();
            } else {
                $pinOtp = new PinOtp();
                $pinOtp->pin_id = $pin->id;
                $pinOtp->otp = $otp;
                $pinOtp->expires_at = Carbon::now()->addSeconds(30);
                $pinOtp->save();
            }
        
            return response()->json(['message' => 'OTP sent successfully'], 200);
        }

        public function verifyOtpAndUpdatePin(Request $request)
        {
            // ✅ Validate request data
            $validatedData = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
                'otp' => 'required|string|min:4|max:6',
                'pin' => 'required|string|min:4|max:6',
            ]);
        
            if ($validatedData->fails()) {
                return response()->json(['errors' => $validatedData->errors()], 422);
            }
        
            // ✅ Step 1: Get user PIN record
            $pin = Pin::where('user_id', $request->user_id)->first();
        
            if (!$pin) {
                return response()->json(['error' => 'Pin record not found for this user'], 404);
            }
        
            // ✅ Step 2: Get latest OTP record for this pin
            $pinOtp = PinOtp::where('pin_id', $pin->id)->latest()->first();
        
            if (!$pinOtp) {
                // no OTP found
                if ($pin->is_varify == false) {
                    $pin->delete();
                }
                return response()->json(['error' => 'No OTP found. Please request a new one.'], 422);
            }
        
            // ✅ Step 3: Check OTP validity
            if ($pinOtp->otp !== $request->otp) {
                if ($pin->is_varify == false) {
                    $pin->delete();
                }
                return response()->json(['error' => 'Invalid OTP entered'], 422);
            }
        
            // ✅ Step 4: Check expiry
            if ($pinOtp->expires_at < now()) {
                if ($pin->is_varify == false) {
                    $pin->delete();
                }
                return response()->json(['error' => 'OTP has expired. Please request a new one.'], 422);
            }
        
            // ✅ Step 5: Update or verify pin
            $pin->pin = $request->pin;
            $pin->is_varify = true;
            $pin->save();
        
            // ✅ Step 6: Delete OTP record after successful verification
            $pinOtp->delete();
        
            return response()->json([
                'message' => 'PIN verified and updated successfully',
                'status' => true
            ], 200);
        }

 private function generateOtp()
    {
        return rand(100000, 999999); 
    }
    // Add methods for update and delete operations as needed
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SocialAuthController extends Controller
{
    public function firebaseLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'nullable|string',
            'google_id' => 'nullable|string',
            'apple_id' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $request->name ?? 'User',
                'email' => $request->email,
                'google_id' => $request->google_id,
                'apple_id' => $request->apple_id,
                'password' => Hash::make(rand(100000, 999999)),
            ]);
        } else {
            if ($request->google_id && empty($user->google_id)) {
                $user->google_id = $request->google_id;
                $user->save();
            }
            if ($request->apple_id && empty($user->apple_id)) {
                $user->apple_id = $request->apple_id;
                $user->save();
            }
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ], 200);
    }
}
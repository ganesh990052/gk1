<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
$user=User::count();
$chit=Chit::count();
    
        return view('dashboard.index',['user'=>$user,'chit'=>$chit]);
    }
    
    
    
    
    
    
        public function show()
        {
            return view('auth.passwords.password-reset');
        }

      public function update(Request $request)
        {
            $request->validate([
                'password' => 'required|min:6|confirmed',
            ]);
        
            $user = Auth::user();
            $user->password = Hash::make($request->password);
            $user->save();
        
            // 🔐 Password update ke baad logout
            Auth::logout();
        
            // Session completely destroy (security)
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        
            // Login page redirect
            return redirect('/chite-fund/sign-in')->with(
                'success',
                'Password updated successfully. Please login again.'
            );
        }

    
    
    
    
    
    
    
    
    
    
    
    
    
}

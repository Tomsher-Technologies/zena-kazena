<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
     // Show the login form
     public function showLoginForm()
     {
         return view('backend.auth.login');
     }

     // Handle login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        // Custom validation logic
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check credentials
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
        
            if ($user->user_type === 'admin' || $user->user_type === 'staff') {
                return redirect()->route('admin.dashboard');
            } else {
                // If usertype doesn't match
                Auth::logout(); // Log the user out
                return back()->withErrors(['password' => trans('messages.account_not_found')]);
            }
           
        } else {
            return back()->withErrors(['password' => 'Invalid credentials']);
        }
    }

    // Logout the user
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}

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
            return redirect()->route('admin.dashboard');
        } else {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }
    }

    // Logout the user
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}

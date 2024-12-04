<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mail;

class AuthController extends Controller
{
    // Show the registration form
    public function showRegistrationForm()
    {
        return view('frontend.auth.register');
    }

    // Handle the registration logic
    public function register(Request $request)
    {
        // Validation for registration form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect('register')
                ->withErrors($validator)
                ->withInput();
        }

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_type' => 'customer',
            'password' => Hash::make($request->password),
        ]);

        $details = [
            'name' => $request->name,
            'subject' => 'Registration Successful - Welcome to '.env('APP_NAME').'!',
            'body' => " <p> Congratulations and welcome to ".env('APP_NAME')."! We are delighted to inform you that your registration has been successfully completed. Thank you for choosing us as your trusted partner.</p><br>

            <p>We are committed to providing you with exceptional service and ensuring that your online shopping experience is smooth and hassle-free. If you have any questions or need assistance, our customer support team is here to help.</p><br>
            <p>Thank you for choosing ".env('APP_NAME').". </p>"
        ];
       
        \Mail::to($request->email)->queue(new \App\Mail\SendMail($details));

        // Log the user in after registration
        Auth::login($user);

        return redirect()->route('home');  // Redirect to home page after registration
    }

    // Show the login form
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    // Handle the login logic
    public function login(Request $request)
    {
        // Validation for login form
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect('login')
                ->withErrors($validator)
                ->withInput();
        }

        // Attempt to log the user in
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('home'); // Redirect to home page if login is successful
        }

        // If authentication fails
        return redirect('login')->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    // Logout the user
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login'); // Redirect to login page after logout
    }
}

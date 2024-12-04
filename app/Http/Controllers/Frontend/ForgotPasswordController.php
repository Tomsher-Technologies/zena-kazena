<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Str;
use Mail;
use DB;
use Hash;

class ForgotPasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(64);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Send email with the reset link (replace with your mail implementation)
        $resetLink = route('password.reset.form', ['email' => $request->email, 'token' => $token]);
        \Mail::send('emails.password-reset', ['resetLink' => $resetLink], function ($message) use ($request) {
            $message->to($request->email)->subject('Password Reset Link');
        });

        return response()->json(['success' => trans('messages.reset_link_success')]);
    }

    public function showResetPasswordForm($email, $token)
    {
        return view('frontend.auth.reset-password', ['token' => $token, 'email' => $email]); // Create this view
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $resetRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord || now()->diffInMinutes($resetRecord->created_at) > 60) {
            return back()->withErrors(['email' => 'Invalid or expired token.']);
        }

        // Reset the user's password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the reset token
        DB::table('password_resets')->where('email', $request->email)->delete();

        session()->flash('message', trans('messages.password_reseted'));
        session()->flash('alert-type', 'success');

        return redirect()->route('login');
    }

}

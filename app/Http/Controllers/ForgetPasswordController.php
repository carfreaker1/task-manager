<?php

namespace App\Http\Controllers;

use App\Mail\ForgetMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class ForgetPasswordController extends Controller
{
    public function CreateForgetPassword(){
        return view('auth.ResetForgetPassword');
    }
    
    public function storeForgotPassword(Request $request) {
        // Validate the email field
        $request->validate([
            'email' => 'required|email'
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
          
        Mail::to($request->email)->send(new ForgetMail(['token'  => $token]));
        return back()->with('message', 'We have e-mailed your password reset link!');

    }
    
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }


    public function showResetPasswordForm($token) { 
        return view('auth.forgetPasswordLink', ['token' => $token]);

     }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'terms' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);
    
        // Fetch the password reset entry
        $updatePassword = DB::table('password_reset_tokens')
                            ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                            ])
                            ->first();
    
        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }
    
        // Update the user's password
        $user = User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);
    
        // Delete the password reset entry
        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();
    
        return redirect('/login')->with('message', 'Your password has been changed!');
    }
}

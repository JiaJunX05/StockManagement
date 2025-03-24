<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showRequestForm() {
        return view('auth.reset_password_email');
    }

    public function sendResetLinkEmail(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        $token = Str::random(60);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        $link = url('/password/reset/' . $token . '?email=' . urlencode($request->email));

        Mail::send('auth.reset_password.email_template', ['link' => $link], function ($message) use ($request) {
            $message->to($request->email)->subject('Reset Password');
        });

        return back()->with('success', 'We have sent you a link to reset your password!');
    }

    public function showResetForm($token=null, Request $request) {
        return view('auth.reset_password.reset')->with(
            ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $updatePassword = DB::table('password_reset_tokens')
                                ->where('email', $request->email)
                                ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();
        return redirect()->route('login')->with('success', 'Your password has been changed!');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

	// use SendsPasswordResetEmails;

	public function forgotPassword(Request $request)
	{
		$request->validate(['email' => 'required|email|exists:users,email']);

		Password::broker()->sendResetLink(
			$request->only('email')
		);

		return response()->json([
			"msg" => "If you've provided registered e-mail, you should get recovery e-mail shortly.",
		], 200);
	}
}
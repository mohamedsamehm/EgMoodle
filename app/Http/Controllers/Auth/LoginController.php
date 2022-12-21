<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Providers\RouteServiceProvider;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

	// use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	// protected $redirectTo = RouteServiceProvider::HOME;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}

	/**
	 * Handle an incoming authentication request.
	 *
	 * @param  \App\Http\Requests\Auth\LoginRequest  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function login(Request $req)
	{
		$credentials = $req->validate([
			'username' => 'required',
			'password' => 'required'
		]);

		if (!$this->guard()->attempt($credentials)) {
			return response()->json([
				'status' => 400,
				'msg' => 'The provided credentials are incorrect.'
			], 200);
		}

		$token = $this->guard()->user()->createToken('auth-token')->plainTextToken;
		$user = User::where('username', $req->username)->with('role')->first();
		$user->lastLogin = Carbon::now()->format('Y-m-d H:i:s');
		$user->save();
		if($user->role->slug == 'admin') {
			$user->ability = [
				array(
					'action' => 'manage',
					'subject' => 'ACL',
				),
				array(
					'action' => 'view',
					'subject' => 'Auth',
				)
			];
		} else if($user->role->slug == 'student') {
			$user->ability = [
				array(
					'action' => 'read',
					'subject' => 'SCL',
				),
				array(
					'action' => 'view',
					'subject' => 'Auth',
				),
				array(
					'action' => 'manage',
					'subject' => 'SPECL',
				)
			];
		} else if($user->role->slug == 'professor') {
			$user->ability = [
				array(
					'action' => 'read',
					'subject' => 'PCL',
				),
				array(
					'action' => 'view',
					'subject' => 'Auth',
				),
				array(
					'action' => 'edit',
					'subject' => 'PECL',
				),
				array(
					'action' => 'manage',
					'subject' => 'SPECL',
				)
			];
		} else if($user->role->slug == 'engineer') {
			$user->ability = [
				array(
					'action' => 'read',
					'subject' => 'ECL',
				),
				array(
					'action' => 'view',
					'subject' => 'Auth',
				),
				array(
					'action' => 'edit',
					'subject' => 'PECL',
				),
				array(
					'action' => 'manage',
					'subject' => 'SPECL',
				)
			];
		}

		return response()->json([
			'status' => 200,
			'accessToken' => $token,
			'refreshToken' => $token,
			'userData' => $user,
		], 200);
	}

	public function refreshToken(Request $req): JsonResponse
	{
		$req->user()->tokens()->delete();

		return response()->json([
			'accessToken' => $req->user()->createToken('auth-token')->plainTextToken,
		]);
	}

	public function logout(Request $req)
	{
		$user = $req->user();
		$user->tokens()->delete();
		$this->guard()->logout();
		return response()->json([
			'status_code' => '200',
			'message' => 'logged out successfully'
		]);
	}

	public function guard($guard = 'web')
	{
		return Auth::guard($guard);
	}

	/**
	 * Attempt to authenticate the request's credentials.
	 *
	 * @return void
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */

	public function authenticate($req)
	{
		// Ensure the login request is not rate limited.
		if (!RateLimiter::tooManyAttempts($this->throttleKey($req), 5)) {
			if (!Auth::attempt($req->only('username', 'password'), $req->boolean('remember'))) {
				RateLimiter::hit($this->throttleKey($req));
				return ['code' => 400, 'msg' => __('auth.failed')];
			} else {
				RateLimiter::clear($this->throttleKey($req));
				return ['code' => 200];
			}
		}

		event(new Lockout($req));

		$seconds = RateLimiter::availableIn($this->throttleKey($req));

		return [
			'code' => 400,
			'username' => trans('auth.throttle', [
				'seconds' => $seconds,
				'minutes' => ceil($seconds / 60),
			])
		];
	}

	/**
	 * Destroy an authenticated session.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy(Request $request)
	{
		$token = $request->user()->token();
		$token->revoke();
		$response = ['message' => 'You have been successfully logged out!'];
		return response($response, 200);
		// Auth::guard('web')->logout();
		// $request->session()->invalidate();
		// $request->session()->regenerateToken();
		// return redirect('/');
	}

	/**
	 * Get the rate limiting throttle key for the request.
	 *
	 * @return string
	 */
	public function throttleKey($req)
	{
		return Str::lower($req->username) . '|' . $req->ip();
	}
}

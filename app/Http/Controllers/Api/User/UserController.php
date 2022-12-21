<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	public function me(Request $request)
	{
		return response()->json($request->user());
	}

	public function updateProfile(Request $request)
	{
		$user = $request->user();
		$request->validate([
			'name' => 'required',
			'email' => 'required|email|unique:users,email,' . $user->id
		]);
		$user->update($request->only('name', 'email'));

		return response()->json([
			'user' => $user,
			'message' => 'profile updated successfully .'
		], 200);
	}

	public function updatePassword(Request $request)
	{
		$user = $request->user();
		$request->validate([
			'password' => 'required|min:6|max:255|confirmed',
		]);
		$user->update(['password' => Hash::make($request->password)]);
		return response()->json([
			'message' => 'password updated successfully .'
		], 200);
	}
}
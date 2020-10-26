<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	public function login(Request $request)
	{
		$login	=	$request->validate([
			'email' => ['required', 'string'],
			'password' => ['required', 'string']
		]);

		if (!Auth::attempt($login)) {
			return response(['message' => 'Invalid credentials.'], 401);
		}

		$accessToken = Auth::user()->createToken('authToken')->accessToken;

		return response(['user' => Auth::user(), 'access_token' => $accessToken]);
	}

	public function register(Request $request)
	{
		$validatedData = $request->validate([
			'name' => 'required|max:255',
			'email' => 'email|required|unique:users',
			'password' => 'required|max:255',
			'cpassword' => 'required|same:password'
		]);

		$validatedData['password'] = bcrypt($request->password);

		$user = User::create([
			'name' => $validatedData['name'],
			'email' => $validatedData['email'],
			'password' => $validatedData['password']
		]);

		$accessToken = $user->createToken('authToken')->accessToken;

		return response(['user' => $user, 'access_token' => $accessToken]);
	}
}

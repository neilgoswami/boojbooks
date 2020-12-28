<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    function loginForm()
    {
        return view('login');
    }

    function login(Request $request)
    {
        $user = new User();
        $response = $user->login($request);
        if ($response->getStatusCode() !== 200) return redirect()->route('login');

        $content = json_decode($response->getContent());
        $request->session()->put('id', $content->user->id);
        $request->session()->put('email', $content->user->email);
        $request->session()->put('access_token', $content->access_token);
        return redirect()->route('lists');
    }

    function registerForm()
    {
        return view('register');
    }

    function register(RegistrationRequest $request)
    {
        $user = new User();
        $response = $user->register($request);
        if ($response->getStatusCode() !== 200) return redirect()->route('register.form');
        return redirect()->route('login');
    }

    function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}

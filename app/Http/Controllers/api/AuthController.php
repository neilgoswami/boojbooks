<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = new User();
        return $user->login($request);
    }

    public function register(RegistrationRequest $request)
    {
        $user = new User();
        return $user->register($request);
    }
}

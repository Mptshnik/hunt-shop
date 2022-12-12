<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Cookie;

class AuthorizationController extends Controller
{
    public function login(Request $request)
    {
        $data = [
            'login' => $request['login'],
            'password' => $request['password']
        ];


        if(!Auth::attempt($data))
        {
            return response(['message' => 'Неправильный логин или пароль']);
        }

        $user = User::where('login', $request['login'])->with('role')->first();

        $token = $user->createToken('JWT')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout()
    {

        Auth::user()->tokens()->delete();

        return [
          'message' => 'Logged out'
        ];
    }
}

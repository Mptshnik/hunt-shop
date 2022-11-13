<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserFormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function store(CreateUserFormRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($request['password']);

        $user = User::create($data);


        $response = [
            'user' => $user,
            'success' => 'true'
        ];

        return response($response, 201);
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function getAuthorizedUser()
    {
        $userLogin = Auth::user()['login'];
        $user = User::where('login', $userLogin)->with('role')->with('employee')->get();

        return $user;
    }
}

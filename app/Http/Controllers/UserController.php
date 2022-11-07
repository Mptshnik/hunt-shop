<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserFormRequest;
use App\Models\User;


class UserController extends Controller
{
    public function store(CreateUserFormRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($request['password']);

        $user = User::create($data);

        //$token = $user->createToken('JWT')->plainTextToken;

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
}

<?php

namespace App\Http\Controllers;
use App\Http\Requests\User\CreateUserFormRequest;
use App\Http\Requests\User\UpdateUserFormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function store(CreateUserFormRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make(($request['password']));

        $user = User::create($data);

        $response = [
            'user' => $user->where('login', $request->login)->with('role')->with('employee')->get(),
            'message' => 'Пользователь успешно создан'
        ];

        return response($response, 201);
    }

    public function update(UpdateUserFormRequest $request, $id)
    {
        $data = $request->validated();
        $data['password'] = Hash::make(($request['password']));

        $user = User::find($id);

        if($user == null)
        {
            return response(['message' => "Пользователь с id=$id не найден"], 404);
        }

        $user->update($data);
        $user->save();

        $response = [
            'user' => $user->where('login', $request->login)->with('role')->with('employee')->get(),
            'message' => 'Пользователь успешно изменен'
        ];

        return response($response, 201);
    }

    public function delete($id)
    {
        $user = User::find($id);

        if($user == null)
        {
            return response(['message' => "Пользователь с id=$id не найден"], 404);
        }

        $user->delete();

        return response(['message' => 'Пользователь успешно удален'], 201);
    }

    public function getAuthorizedUser()
    {
        $userLogin = Auth::user()['login'];
        $user = User::where('login', $userLogin)->with('role')->with('employee')->get();

        return $user;
    }

    public function getAll()
    {
        return User::with('role')->with('employee')->get();
    }

    public function getOne($id)
    {
        $user = User::find($id);

        if($user == null)
        {
            return response(['message' => "Пользователь с id=$id не найден"], 404);
        }

        return response($user->with('role')->with('employee')->get(), 201);
    }
}

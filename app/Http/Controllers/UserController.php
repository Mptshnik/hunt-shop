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

        $id = $user->id;

        $response = [
            'user' => $user->where('id', $id)->with('role')->with('employee')->first(),
            'message' => 'Пользователь успешно создан'
        ];

        return response($response, 201);
    }

    public function update(UpdateUserFormRequest $request, $id)
    {
        $user = User::find($id);

        if($user == null)
        {
            return response(['message' => "Пользователь с id=$id не найден"], 404);
        }

        $data = $request->validated();
        $data['password'] = Hash::make(($request['password']));

        $user->update($data);
        $user->save();

        $user_updated = User::where('login', $request->login)->with('role')->with('employee')->first();

        $response = [
            'user' => $user_updated,
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
        return Auth::user()->where('login', Auth::user()->login)->with('role')->with('employee')->first();
    }

    public function getAll()
    {
        return response(User::with('role')->with('employee')->get(), 201);
    }

    public function getOne($id)
    {
        $user = User::where('id', $id)->with('role')->with('employee')->first();
        if($user === null)
        {
            return response(['message' => "Пользователь с id=$id не найден"], 404);
        }

        return response($user, 201);
    }
}

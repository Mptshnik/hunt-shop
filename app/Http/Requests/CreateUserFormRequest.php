<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateUserFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      /*  $user = Auth::user();
        return $user->role_id == Role::IS_ADMIN;*/
         return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'login' => 'required|unique:users|min:5',
            'password' => 'required|min:5',
            'agreement' => 'accepted',
            'role_id' => 'required|integer',
            'employee_id' => 'nullable|integer'
        ];
    }


    public function messages()
    {
        return [
            'login.required' => 'Логин обязателен',
            'login.unique' => 'Логин должен быть уникальным',
            'login.min' => 'Минимальная длина логина - 5 символов',
            'password.required' => 'Пароль обязателен',
            'password.min' => 'Минимальная длина пароля - 5 символов',
            'agreement.required' => 'Соглашение обязательно',
            'role_id.required' => 'Роль обязательна'
        ];
    }
}

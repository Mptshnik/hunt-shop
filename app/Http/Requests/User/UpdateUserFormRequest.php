<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'login' => ['required', 'min:5', 'max:25', Rule::unique('users')->ignore($this->id)],
            'password' => 'required|min:5|max:25',
            'agreement' => 'accepted',
            'role_id' => 'required|integer',
            'employee_id' => ['required', 'integer', Rule::unique('users')->ignore($this->id)]
        ];
    }


    public function messages()
    {
        return [
            'login.required' => 'Логин обязателен',
            'login.unique' => 'Логин должен быть уникальным',
            'login.min' => 'Минимальная длина логина - 5 символов',
            'login.max' => 'Максимальная длина логина - 25 символов',
            'password.required' => 'Пароль обязателен',
            'password.min' => 'Минимальная длина пароля - 5 символов',
            'password.max' => 'Максимальная длина пароля - 25 символов',
            'agreement.accepted' => 'Соглашение обязательно',
            'role_id.required' => 'Роль обязательна',
            'employee_id.required' => 'Сотрудник обязателен',
            'employee_id.unique' => 'У выбранного сотрудника уже есть аккаунт'
        ];
    }
}

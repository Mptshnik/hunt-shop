<?php

namespace App\Http\Requests\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeFormRequest extends FormRequest
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

     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'last_name' => 'required|min:1|max:255|string',
            'first_name' => 'required|min:1|max:255|string',
            'middle_name' => 'nullable|string',
            'passport_series' => ['required', 'digits_between:4,4', Rule::unique('employees')->ignore($this->id)],
            'passport_number' => ['required', 'digits_between:6,6', Rule::unique('employees')->ignore($this->id)],
            'birthday_date' => 'required|date:d-m-Y|before:today',
            'post_id' => 'nullable|integer'
        ];
    }

    public function messages()
    {
        return [
            'last_name.required' => 'Фамилия обязательная',
            'last_name.min' => 'Минимальная длина фамилии 1 символ',
            'last_name.max' => 'Максимальная длина фамилии 255 символов',
            'last_name.string' => 'Фамилия должна содержать буквы',

            'first_name.required' => 'Имя обязательно',
            'first_name.min' => 'Минимальная длина имени 1 символ',
            'first_name.max' => 'Максимальная длина имени 255 символов',
            'first_name.string' => 'Имя должно содержать буквы',

            'middle_name.string' => 'Отчество должно содержать буквы',

            'passport_series.required' => 'Серия паспорта обязательна',
            'passport_series.digits_between' => 'Длина серии паспорта 4 символа',
            'passport_series.unique' => 'Серия паспорта должна быть уникальной',

            'passport_number.required' => 'Номер паспорта обязателен',
            'passport_number.digits_between' => 'Длина номера паспорта 6 символов',
            'passport_number.unique' => 'Номер паспорта должен быть уникальным',

            'birthday_date.required' => 'Дата рождения обязательна',
            'birthday_date.date' => 'Формат даты ДД-ММ-ГГГГ',
            'birthday.before' => 'Дата рождения не может быть больше сегодняшей',

            'post_id.integer' => 'Некорректная должность'
        ];
    }
}

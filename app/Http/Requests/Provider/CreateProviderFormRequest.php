<?php

namespace App\Http\Requests\Provider;

use Illuminate\Foundation\Http\FormRequest;

class CreateProviderFormRequest extends FormRequest
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
            'name' => 'required|unique:providers|max:255',
            'phone_number' => 'required|unique:providers|digits_between:11,11|numeric',
            'taxpayer_number' => 'required|unique:providers|digits_between:12,12|numeric',
            'address' => 'required|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Наименование поставщика обязательно',
            'name.unique' => 'Поставщик с таким именем уже существует',
            'name.max' => 'Максимальная длина наименования 255 символов',

            'phone_number.required' => 'Номер телефона обязателен',
            'phone_number.unique' => 'Поставщик с таким номером телефона уже существует',
            'phone_number.digits_between' => 'Номер телефона должен состоять из 11 цифр',
            'phone_number.numeric' => 'Номер телефона должен состоять из цифр',

            'taxpayer_number.required' => 'ИНН поставщика обязателен',
            'taxpayer_number.unique' => 'Поставщик с таким ИНН уже существует',
            'taxpayer_number.digits_between' => 'ИНН должен состоять из 12 цифр',
            'taxpayer_number.numeric' => 'ИНН должен состоять из цифр',

            'address.required' => 'Адрес обязателен',
            'address.max' => 'Максимальная длина адреса 1000 символов'
        ];
    }
}

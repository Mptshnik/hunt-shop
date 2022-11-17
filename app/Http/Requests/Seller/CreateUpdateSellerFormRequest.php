<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateSellerFormRequest extends FormRequest
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
            'name' => 'required|max:255|string',
            'taxpayer_number' => 'required|digits_between:12,12',
            'juridical_address' => 'required|max:255',
            'physical_address' => 'required|max:255'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Наименование организации обязательно',
            'name.max' => 'Максимальная длина наименования организации 255 символов',
            'name.string' => 'Наименование организации должно содержать буквы или цифры',

            'taxpayer_number.required' => 'ИНН организации обязателен',
            'taxpayer_number.digits_between' => 'ИНН должен состоять из 12 цифр',

            'juridical_address.required' => 'Юридический адрес обязателен',
            'juridical_address.max' => 'Максимальная длина юридического адреса 255 символов',

            'physical_address.required' => 'Физический адрес обязателен',
            'physical_address.max' => 'Максимальная длина физического адреса 255 символов'
        ];
    }
}

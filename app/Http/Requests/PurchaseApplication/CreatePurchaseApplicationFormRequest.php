<?php

namespace App\Http\Requests\PurchaseApplication;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseApplicationFormRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'number' => 'required|numeric|digits_between:8,8|unique:purchase_applications',
            'content' => 'required|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Наменование обязательно',
            'name.string' => 'Наименование должно быть строкой',
            'name.max' => 'Максимальная длина наименования 255 символов',

            'number.required' => 'Номер заявки обязателен',
            'number.numeric' => 'Номер заявки должен состоять из цифр',
            'number.digits_between' => 'Длина номера должна составлять 8 символов',
            'number.unique' => 'Заявка с таким номером уже существует',

            'content.required' => 'Содержание заявки обязательно',
            'content.max' => 'Максимальная длина содержания заявки 1000 символов',
        ];
    }
}

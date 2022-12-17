<?php

namespace App\Http\Requests\ItemInvoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateItemInvoiceFormRequest extends FormRequest
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
            'seller_id' => ['required', 'min:0', 'integer', Rule::exists('sellers', 'id')],
            'provider_id' => ['required', 'min:0', 'integer', Rule::exists('providers', 'id')],
            'number' => 'required|digits_between:1,16|unique:item_invoices',
            'date' => 'required|date_format:d-m-Y|before:tomorrow'
        ];
    }

    public function messages()
    {
        return [
            'seller_id.required' => 'Организация обязательна',
            'seller_id.min' => 'ID организации должен быть больше и равен 0',
            'seller_id.integer' => 'ID организации дожен быть числовым',
            'seller_id.exists' => 'Организация с таким ID не найдена',

            'provider_id.required' => 'Поставщик обязателен',
            'provider_id.min' => 'ID поставщика должен быть больше и равен 0',
            'provider_id.integer' => 'ID поставщика дожен быть числовым',
            'provider_id.exists' => 'Поставщик с таким ID не найден',

            'number.required' => 'Номер товарной накладной обязателен',
            'number.digits_between' => 'Длина номера должна находиться в диапазоне от 1 до 16 цифр',
            'number.unique' => 'Накладная с таким номером уже существует',

            'date.required' => 'Дата обязательна',
            'date.date_format' => 'Дата должна быть в формате ДД-ММ-ГГГГ',
            'date.before' => 'Дата не может быть больше текущей',
        ];
    }
}

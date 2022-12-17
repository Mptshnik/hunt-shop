<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateItemFormRequset extends FormRequest
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
            'item_category_id' => ['required', 'integer', 'min:0', Rule::exists('item_categories', 'id')],
            'promotion_id' => ['nullable', 'integer', 'min:0', Rule::exists('promotions', 'id')],
            'item_invoice_id' => ['required', 'integer', 'min:0', /*Rule::exists('item_invoices', 'id')*/],
            'name' => ['required', 'string', 'max:255', Rule::unique('items')->ignore($this->id)],
            'number' => ['required', 'digits_between:8,8', Rule::unique('items')->ignore($this->id)],
            'count' => 'required|integer|min:1|max:1000000000',
            'description' => 'max:1000',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'image' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'item_category_id.required' => 'Категория товара обязательна',
            'item_category_id.integer' => 'ID категории должен быть целочисленным',
            'item_category_id.min' => 'ID категории должен быть больше либо равен 0',
            'item_category_id.exists' => 'Категория товара с таким ID не найдена',

            'promotion_id.integer' => 'ID акции должен быть целочисленным',
            'promotion_id.min' => 'ID акции должен быть больше либо равен 0',
            'promotion_id.exists' => 'Акция с таким ID не найдена',

            'item_invoice_id.required' => 'Товарная накладаная обязательна',
            'item_invoice_id.integer' => 'ID товарной накладной должен быть целочисленным',
            'item_invoice_id.min' => 'ID товарной накладной должен быть больше либо равен 0',
            //'item_invoice_id.exists' => 'Товарная накладная с таким ID не найдена',

            'name.required' => 'Наименование товара обязательно',
            'name.string' => 'Наименование должно быть строкой',
            'name.max' => 'Максимальная длина наименования 255 символов',
            'name.unique' => 'Товар с таким наименованием уже существует',

            'number.required' => 'Артикул товара обязателен',
            'number.digits_between' => 'Артикул должен содержать 8 цифр',
            'number.unique' => 'Товар с таким артикулом уже существует',

            'count.required' => 'Количество обязательно',
            'count.integer' => 'Количество должно быть целым числом',
            'count.min' => 'Минимальное количетсво товара 1',
            'count.max' => 'Максимальное количетсво товара 1 000 000 000',

            'description.max' => 'Максимальная длина описания 1000 символов',

            'price.required' => 'Стоимоить обязательна',
            'price.numeric' => 'Стоимость должна быть числом',

            'sale_price.numeric' => 'Стоимость по акции должна быть числом',
        ];
    }
}

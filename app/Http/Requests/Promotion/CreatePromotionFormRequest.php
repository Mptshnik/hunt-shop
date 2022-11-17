<?php

namespace App\Http\Requests\Promotion;

use Illuminate\Foundation\Http\FormRequest;

class CreatePromotionFormRequest extends FormRequest
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
            'name' => 'required|max:255|unique:promotions',
            'description' => 'required|max:1000',
            'start_date' => 'required|date_format:d-m-Y|after:yesterday',
            'end_date' => 'required|date_format:d-m-Y|after:tomorrow',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Название акции обязательно',
            'name.max'=> 'Максимальная длина названия 255 символов',
            'name.unique' => 'Акция с таким названием уже существует',

            'description.required' => 'Описание обязательно',
            'description.max' => 'Максимальная длина описания 1000 символов',

            'start_date.required' => 'Дата начала акции обязательна',
            'start_date.date_format' => 'Дата начала акции должна соответствовать формату ДД-ММ-ГГГГ',
            'start_date.after' => 'Акция может начинаться только с сегодняшнего дня',

            'end_date.required' => 'Дата окончания акции обязательна',
            'end_date.date_format' => 'Дата окончания акции должна соответствовать формату ДД-ММ-ГГГГ',
            'end_date.end' => 'Дата окончания акции должны быть больше сегодняшней',
        ];
    }
}

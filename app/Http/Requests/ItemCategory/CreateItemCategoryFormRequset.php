<?php

namespace App\Http\Requests\ItemCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateItemCategoryFormRequset extends FormRequest
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
            'name' => ['required', 'max:255', 'string', Rule::unique('item_categories')->ignore($this->id)],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Наименование категории обязательно',
            'name.max' => 'Максимальная длина наименования 255 символов',
            'name.string' => 'Наименование должно быть строкой',
            'name.unique' => 'Категория с таким наименованием уже существует',
        ];
    }
}

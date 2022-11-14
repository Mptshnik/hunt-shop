<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostFormRequest extends FormRequest
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
            'name' => ['required', 'max:255', 'min:1',Rule::unique('posts')->ignore($this->id)],
            'description' => 'required|min:1|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Наименование должности обязательно',
            'name.max' => 'Максимальная длина наименования 255 символов',
            'name.unique' => 'Должность с таким названием уже существует',
            'name.min' => 'Минимальная длина наименования 1 символ',

            'description.required' => 'Описание обязательно',
            'description.max' => 'Максимальная длина описания 1000 символов',
            'description.min' => 'Минимальная длина описания 1 символ'
        ];
    }
}

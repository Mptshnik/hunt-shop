<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderFormRequest extends FormRequest
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
            'client_id' => ['required', 'integer', Rule::exists('clients', 'id')],
        ];
    }

    public function messages()
    {
        return [
            'client_id.exists' => 'Клиент с таким id не найден',
            'client_id.required' => 'ID покупателя обязателен',
            'client_id.integer' => 'ID плкупателя должен быть целым числом',
        ];
    }
}

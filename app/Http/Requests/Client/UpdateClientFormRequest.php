<?php

namespace App\Http\Requests\Client;

use App\Models\JuridicalStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientFormRequest extends FormRequest
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
        $person_status = JuridicalStatus::IS_PERSON;
        $organisation_status = JuridicalStatus::IS_ORGANISATION;
        return [
            'last_name' => "string|required_if:juridical_status_id,==,$person_status",
            'first_name' => "string|required_if:juridical_status_id,==,$person_status",
            'middle_name' => 'nullable|string',
            'phone_number' => ['required', 'digits_between:11,11' ,
                Rule::unique('organisations')->ignore($this->organisation_id),
                Rule::unique('people')->ignore($this->person_id)],
            'organisation_name' => "required_if:juridical_status_id,==,$organisation_status",
            'taxpayer_number' => ['required', 'digits_between:10,12',
                Rule::unique('clients')->ignore($this->id)],
            'juridical_address' => "max:255|required_if:juridical_status_id,==,$organisation_status",
            'physical_address' => 'max:255|required',
            'juridical_status_id' => 'integer|required'
        ];
    }

    public function messages()
    {
        return [
            'last_name.string' => 'Фамилия должна являться строкой',
            'last_name.required_if' => 'Фамилия обязательна',

            'first_name.string' => 'Имя должно являться строкой',
            'first_name.required_if' => 'Имя обязательно',

            'middle_name.string' => 'Отчество должно являться строкой',

            'phone_number.required' => 'Номер телефона обязателен',
            'phone_number.digits_between' => 'Номер телефона должен состоять из 11 цифр',
            'phone_number.unique' => 'Клиент с таким номером телефона уже существует',

            'organisation_name.required_if' => 'Наименование организации обязательно',

            'taxpayer_number.required' => 'ИИН обязателен',
            'taxpayer_number.unique' => 'Клиент с таким ИНН уже существует',
            'taxpayer_number.digits_between' => 'ИНН должен состоять из 10 или 12 цифр',

            'juridical_address.required_if' => 'Юридический адрес обязателен',
            'juridical_address.max' => 'Максимальная длина юридического адреса 255 символов',

            'physical_address.required' => 'Физический адрес обязателен',
            'physical_address.max' => 'Максимальная длина физического адреса 255 символов',

            'juridical_status_id.required' => 'Юридический статус обязателен'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnonymousRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|min:2',
            'phone' => 'required|phone:RU',
            'subject' => 'required',
            'gym_id' => 'integer|nullable'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'phone' => 'Номер телефона указан неверно',
        ];
    }
}

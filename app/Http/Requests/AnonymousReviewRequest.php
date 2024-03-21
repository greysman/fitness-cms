<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnonymousReviewRequest extends FormRequest
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
            'name' => 'required|min:2',
            'text' => 'required',
            'comment' => 'max:0|nullable'
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
            'text' => 'Поле является обязательным',
            'comment.max' => 'Похоже Вы - робот :('
        ];
    }
}

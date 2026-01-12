<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetailRequest extends FormRequest
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
            'work-time' => [],
            'rest-time' => [],
            'description' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'description.required' => '備考を入力してください',
        ];
    }
}

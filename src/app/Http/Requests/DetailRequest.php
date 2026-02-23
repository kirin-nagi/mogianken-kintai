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
            'start_work' => ['required'],
            'end_work' => ['required'],
            'rest_start' => ['required'],
            'rest_end' => ['required'],
            'description' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'description.required' => '備考を入力してください',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
{
    dd($validator->errors()->toArray());
}
}

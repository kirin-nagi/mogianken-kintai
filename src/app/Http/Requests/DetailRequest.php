<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator){
            $startWork = $this->input('start_work');
            $endWork = $this->input('end_work');
            $restStart = $this->input('rest_start');
            $restEnd = $this->input('rest_end');

            if($startWork && $endWork && $startWork >= $endWork){
                $validator->errors()->add(
                    'start_work',
                    '出勤時間もしくは退勤時間が不適切な値です'
                );

                $validator->errors()->add(
                    'end_work',
                    '出勤時間もしくは退勤時間が不適切な値です'
                );
            }

            if($restStart && $startWork && $restStart < $startWork) {
                $validator->errors()->add(
                    'rest_start',
                    '休憩時間が不適切な値です'
                );
            }

            if($restStart && $endWork && $restStart >= $endWork){
                $validator->errors()->add(
                    'rest_start',
                    '休憩時間が不適切な値です'
                );
            }

            if($restEnd && $endWork && $restEnd >= $endWork){
                $validator->errors()->add(
                    'rest_end',
                    '休憩時間もしくは退勤時間が不適切な値です'
                );
            }
        });
    }
}

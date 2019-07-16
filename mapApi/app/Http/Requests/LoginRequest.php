<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Response\CommonResponse;


class LoginRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'email' => ['required','email'],
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => config('const_message.ERROR_EMAIL_REQUIRED'),
            'password.required' => config('const_message.ERROR_PASSWORD_REQUIRED')
        ];
    }
    protected function failedValidation( Validator $validator )
    {
        $response = new CommonResponse();
        $response->status = config('const_http_status.BAD_REQUEST_400');
        $response->message =  $validator->errors()->toArray();

        throw new HttpResponseException(
            response()->json( $response, config('const_http_status.OK_200'))
        );
    }
}

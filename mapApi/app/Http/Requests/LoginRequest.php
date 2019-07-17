<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Response\CommonResponse;

use App\Http\Requests\CommonAbstractRequest;

class LoginRequest extends CommonAbstractRequest
{
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
            'email.email' => config('const_message.ERROR_EMAIL_FORMAT'),
            'password.required' => config('const_message.ERROR_PASSWORD_REQUIRED')
        ];
    }
}

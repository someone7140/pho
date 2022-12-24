<?php

namespace App\Http\Requests\UserAccountRegister;

use App\Http\Requests\Common\CommonAbstractRequest;

class RegisterByTwitterAuthCodeRequest extends CommonAbstractRequest
{
    public function rules()
    {
        return [
            'authCode' => ['required', 'string'],
            'codeVerifier' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'authCode.required' => 'authCode is required',
            'codeVerifier.required' => 'codeVerifier is required',
        ];
    }
}

<?php

namespace App\Http\Requests\UserAccountAuth;

use App\Http\Requests\Common\CommonAbstractRequest;

class LoginByTwitterAuthCodeRequest extends CommonAbstractRequest
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

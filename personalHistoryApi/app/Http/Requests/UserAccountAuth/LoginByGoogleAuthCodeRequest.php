<?php

namespace App\Http\Requests\UserAccountAuth;

use App\Http\Requests\Common\CommonAbstractRequest;

class LoginByGoogleAuthCodeRequest extends CommonAbstractRequest
{
    public function rules()
    {
        return [
            'authCode' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'authCode.required' => 'authCode is required',
        ];
    }
}

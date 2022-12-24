<?php

namespace App\Http\Requests\UserAccountRegister;

use App\Http\Requests\Common\CommonAbstractRequest;

class RegisterByGoogleAuthCodeRequest extends CommonAbstractRequest
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

<?php

namespace App\Http\Requests\UserAccountRegister;

use App\Http\Requests\Common\CommonAbstractRequest;

class AuthEmailRegisteredRequest extends CommonAbstractRequest
{
    public function rules()
    {
        return [
            'id' => ['required', 'string', 'max:300'],
            'password' => ['required', 'string', 'min:6', 'max:100'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'id is required',
            'password.required' => 'password is required',
            'password.min' => 'password is upper 6 length',
            'password.max' => 'password is under 100 length',
        ];
    }
}

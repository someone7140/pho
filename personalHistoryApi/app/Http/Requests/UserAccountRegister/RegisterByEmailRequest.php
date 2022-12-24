<?php

namespace App\Http\Requests\UserAccountRegister;

use App\Http\Requests\Common\CommonAbstractRequest;

class RegisterByEmailRequest extends CommonAbstractRequest
{
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email', 'max:300'],
            'password' => ['required', 'string', 'min:6', 'max:100'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'email is required',
            'email.email' => 'email is bad format',
            'email.max' => 'email is under 300 length',
            'password.required' => 'password is required',
            'password.min' => 'password is upper 6 length',
            'password.max' => 'password is under 100 length',
        ];
    }
}

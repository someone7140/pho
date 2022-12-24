<?php

namespace App\Http\Requests\UserAccountAuth;

use App\Http\Requests\Common\CommonAbstractRequest;

class ChangePasswordRequest extends CommonAbstractRequest
{
    public function rules()
    {
        return [
            'password' => ['required', 'string', 'min:6', 'max:100'],
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'password is required',
            'password.min' => 'password is upper 6 length',
            'password.max' => 'password is under 100 length',
        ];
    }
}

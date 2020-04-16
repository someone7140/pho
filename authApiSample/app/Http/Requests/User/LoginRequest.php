<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;

use App\Http\Requests\Common\CommonAbstractRequest;

class LoginRequest extends CommonAbstractRequest
{
    public function rules() {
        return [
            'email' => ['required'],
            'password' => ['required'],
            'role' => [Rule::in([
                config('const_user.ROLE_CUSTOMER'),
                config('const_user.ROLE_STORE'),
                config('const_user.ROLE_ADMIN')
            ])]
        ];
    }

    public function messages()
    {
        return [
            'email.required' => config('const_message.INPUT_ERROR_REQUIRED'),
            'password.required' => config('const_message.INPUT_ERROR_REQUIRED'),
            'role.in' => config('const_message.INPUT_ERROR_ILLEGAL')
        ];
    }
}

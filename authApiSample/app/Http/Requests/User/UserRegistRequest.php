<?php

namespace App\Http\Requests\User;

use App\Http\CustomValidation\UserEmailRegisteredCheck;
use App\Http\Requests\Common\CommonAbstractRequest;

class UserRegistRequest extends CommonAbstractRequest
{
    public function rules() {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', new UserEmailRegisteredCheck],
            'password' => ['required', 'min:6']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => config('const_message.INPUT_ERROR_REQUIRED'),
            'email.required' => config('const_message.INPUT_ERROR_REQUIRED'),
            'email.email' => config('const_message.INPUT_ERROR_EMAIL_FORMAT'),
            'password.required' => config('const_message.INPUT_ERROR_REQUIRED'),
            'password.min' => config('const_message.INPUT_ERROR_LENGTH')            
        ];
    }
}

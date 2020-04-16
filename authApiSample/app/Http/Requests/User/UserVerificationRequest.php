<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;

use App\Http\Requests\Common\CommonAbstractRequest;

class UserVerificationRequest extends CommonAbstractRequest
{
    public function rules() {
        return [
            'user_id' => ['required'],
            'password' => ['required'],
            'token' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => config('const_message.INPUT_ERROR_REQUIRED'),
            'password.required' => config('const_message.INPUT_ERROR_REQUIRED'),
            'token.required' => config('const_message.INPUT_ERROR_REQUIRED')
        ];
    }
}

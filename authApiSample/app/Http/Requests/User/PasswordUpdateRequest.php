<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;

use App\Http\Requests\Common\CommonAbstractRequest;

class PasswordUpdateRequest extends CommonAbstractRequest
{
    public function rules() {
        return [
            'user_id' => ['required'],
            'password' => ['required', 'min:6'],
            'token' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => config('const_message.INPUT_ERROR_REQUIRED'),
            'password.required' => config('const_message.INPUT_ERROR_REQUIRED'),
            'password.min' => config('const_message.INPUT_ERROR_LENGTH'),
            'token.required' => config('const_message.INPUT_ERROR_REQUIRED')
        ];
    }
}

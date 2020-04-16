<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;

use App\Http\Requests\Common\CommonAbstractRequest;

class PasswordResetRequest extends CommonAbstractRequest
{
    public function rules() {
        return [
            'email' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => config('const_message.INPUT_ERROR_REQUIRED'),
        ];
    }
}

<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Common\CommonAbstractRequest;

class UserEditRequest extends CommonAbstractRequest
{
    public function rules() {
        return [
            'password' => ['min:6']
        ];
    }

    public function messages()
    {
        return [
            'password.min' => config('const_message.INPUT_ERROR_LENGTH')
        ];
    }
}

<?php

namespace App\Http\Requests\UserAccountRegister;

use App\Http\Requests\Common\CommonAbstractRequest;

class RegisterUserAccountRequest extends CommonAbstractRequest
{
    public function rules()
    {
        return [
            'userId' => ['required', 'string', 'alpha_dash', 'max:100'],
            'name' => ['required', 'string', 'max:200'],
            'isAccountOpen' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'userId.required' => 'userId is required',
            'userId.alpha_dash' => 'userId is alphabet or number',
            'userId.max' => 'userId is under 100 length',
            'name.required' => 'name is required',
            'name.max' => 'name is under 200 length',
            'isAccountOpen.required' => 'isAccountOpen is required',
        ];
    }
}

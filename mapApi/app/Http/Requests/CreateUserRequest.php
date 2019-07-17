<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Response\CommonResponse;
use App\Rules\EmailDuplicateCheck;
use App\User;

use App\Http\Requests\CommonAbstractRequest;

class CreateUserRequest extends CommonAbstractRequest
{
    public function rules() {
        return [
            'name' => 'required',
            'email' => ['required','email', new EmailDuplicateCheck],
            'password' => 'required|min:6',
            'password_confirm' => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => config('const_message.ERROR_NAME_REQUIRED'),
            'email.required' => config('const_message.ERROR_EMAIL_REQUIRED'),
            'email.email' => config('const_message.ERROR_EMAIL_FORMAT'),
            'password.required' => config('const_message.ERROR_PASSWORD_REQUIRED'),
            'password.min' => config('const_message.ERROR_PASSWORD_MIN'),
            'password_confirm.required' => config('const_message.ERROR_PASSWORD_CONFIRM_REQUIRED'),        
            'password_confirm.same' => config('const_message.ERROR_PASSWORD_CONFIRM_SAME')
        ];
    }
}

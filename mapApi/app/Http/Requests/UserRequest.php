<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Response\CommonResponse;
use App\Rules\EmailDuplicateCheck;
use App\Rules\ApiTokenExpireCheck;
use App\User;

use App\Http\Requests\CommonAbstractRequest;

class UserRequest extends CommonAbstractRequest
{
    public function rules() {
        $password =  $this->request->get('password');
        if ($this->path() == 'api/user/create') {
            return [
                'name' => 'required',
                'email' => ['required','email', new EmailDuplicateCheck],
                'password' => 'required|min:6',
                'password_confirm' => 'required|same:password',
            ];
        } else {
            $user = Auth::user();
            $emailCheck = [];
            if ($user->email == $this->request->get('email')) {
                $emailCheck = ['required','email'];
            } else {
                $emailCheck = ['required','email', new EmailDuplicateCheck];
            }
            if (isset($password) && mb_strlen($password)) {
                return [
                    'name' => 'required',
                    'email' => $emailCheck,
                    'password' => 'required|min:6',
                    'password_confirm' => 'required|same:password',
                    'api_token' => [new ApiTokenExpireCheck]
                ];
            } else {
                return [
                    'name' => 'required',
                    'email' => $emailCheck,
                    'api_token' => [new ApiTokenExpireCheck]
                ];
            }
        } 
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

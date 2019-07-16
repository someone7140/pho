<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Response\CommonResponse;
use App\Rules\EmailDuplicateCheck;
use App\User;


class CreateUserRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

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
    protected function failedValidation( Validator $validator )
    {
        $response = new CommonResponse();
        $response->status = config('const_http_status.BAD_REQUEST_400');
        $response->message =  $validator->errors()->toArray();

        throw new HttpResponseException(
            response()->json( $response, config('const_http_status.OK_200'))
        );
    }
}

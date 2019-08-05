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

class EventRequest extends CommonAbstractRequest
{
    public function rules() {
        return [
            'title' => 'required',
            'detail' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => config('const_message.ERROR_EVENT_TITLE_REQUIRED'),
            'detail.required' => config('const_message.ERROR_EVENT_DETAIL_REQUIRED')
        ];
    }
}

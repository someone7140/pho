<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Response\CommonResponse;

use App\Http\Requests\CommonAbstractRequest;
use App\Rules\ApiTokenExpireCheck;

class OnlyApiTokenRequest extends CommonAbstractRequest
{
    public function rules() {
        return [
            'api_token' => [new ApiTokenExpireCheck]
        ];
    }

    public function messages()
    {
        return [];
    }
}

<?php

namespace App\Http\Requests\History;

use App\Http\Requests\Common\CommonAbstractRequest;

class GetHistoryInfoByUserRequest extends CommonAbstractRequest
{
    public function rules()
    {
        return [
            'userId' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'userId.required' => 'userId is required',
        ];
    }
}

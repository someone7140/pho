<?php

namespace App\Http\Requests\History;

use App\Http\Requests\Common\CommonAbstractRequest;

class HistoryRegisterRequest extends CommonAbstractRequest
{
    public function rules()
    {
        return [
            'categoryId' => 'required',
            'historyRecords' => 'array',
        ];
    }

    public function messages()
    {
        return [
            'categoryId.required' => 'categoryId is required',
            'historyRecords.array' => 'historyRecords is array',
        ];
    }
}

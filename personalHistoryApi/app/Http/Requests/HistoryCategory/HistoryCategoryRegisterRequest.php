<?php

namespace App\Http\Requests\HistoryCategory;

use App\Http\Requests\Common\CommonAbstractRequest;

class HistoryCategoryRegisterRequest extends CommonAbstractRequest
{
    public function rules()
    {
        return [
            'categories' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'categories.required' => 'categories is required',
            'categories.array' => 'categories is array',
        ];
    }
}

<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CommonAbstractRequest extends FormRequest
{
    public function rules()
    {
        return [];
    }

    protected function failedValidation(Validator $validator)
    {
        // 入力チェックエラー時のレスポンス
        $messages = $validator->errors()->toArray();
        $res = response()->json([
            'message' => $messages,
        ], Response::HTTP_BAD_REQUEST);
        throw new HttpResponseException($res);
    }
}

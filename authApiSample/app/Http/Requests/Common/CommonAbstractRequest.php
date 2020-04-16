<?php

namespace App\Http\Requests\Common;

use App\Http\Response\Common\CommonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CommonAbstractRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [];
    }

    public function messages()
    {
        return [];
    }
    protected function failedValidation( Validator $validator )
    {
        // 入力チェックエラー時のレスポンス
        $response = new CommonResponse();
        $response->status = config('const_http_status.BAD_REQUEST_400');
        $response->errors =  $validator->errors()->toArray();

        throw new HttpResponseException(
            // HTTPステータスは200で返す
            response()->json( $response, config('const_http_status.OK_200'))
        );
    }
}

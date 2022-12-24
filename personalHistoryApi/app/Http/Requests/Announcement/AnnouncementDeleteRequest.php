<?php

namespace App\Http\Requests\Announcement;

use App\Http\Requests\Common\CommonAbstractRequest;

class AnnouncementDeleteRequest extends CommonAbstractRequest
{
    public function rules()
    {
        return [
            'id' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'id is required',
        ];
    }
}

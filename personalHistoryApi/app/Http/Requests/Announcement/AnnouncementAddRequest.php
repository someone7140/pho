<?php

namespace App\Http\Requests\Announcement;

use App\Http\Requests\Common\CommonAbstractRequest;

class AnnouncementAddRequest extends CommonAbstractRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
            'announcementHtml' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'title is required',
            'announcementHtml.required' => 'announcementHtml is announcementHtml',
        ];
    }
}

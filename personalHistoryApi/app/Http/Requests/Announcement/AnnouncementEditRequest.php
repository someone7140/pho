<?php

namespace App\Http\Requests\Announcement;

use App\Http\Requests\Common\CommonAbstractRequest;

class AnnouncementEditRequest extends CommonAbstractRequest
{
    public function rules()
    {
        return [
            'id' => 'required',
            'title' => 'required',
            'announcementHtml' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'id is required',
            'title.required' => 'title is required',
            'announcementHtml.required' => 'announcementHtml is announcementHtml',
        ];
    }
}

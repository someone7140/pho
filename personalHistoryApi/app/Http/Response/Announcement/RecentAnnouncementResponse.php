<?php

namespace App\Http\Response\Announcement;

use App\Http\Response\Common\CommonAbstractResponse;

class RecentAnnouncementResponse extends CommonAbstractResponse
{
    public function __construct(
        public string $id,
        public string $title,
        public string $createdAt
    ) {
    }
}

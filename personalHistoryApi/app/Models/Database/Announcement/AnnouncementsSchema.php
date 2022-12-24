<?php

namespace App\Models\Database\Announcement;

use \DateTime;


class AnnouncementsSchema
{
    public string $id;
    public string $title;
    public string $announcementHtml;
    public DateTime $createdAt;

    public function __construct(
        $eloquentModel,
    ) {
        $this->id = $eloquentModel->_id;
        $this->title = $eloquentModel->title;
        $this->announcementHtml = $eloquentModel->announcement_html;
        $this->createdAt = $eloquentModel->created_at;
    }
}

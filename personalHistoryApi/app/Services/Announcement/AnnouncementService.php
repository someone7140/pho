<?php

namespace App\Services\Announcement;

use App\Models\Database\Announcement\AnnouncementsSchema;
use App\Repositories\AnnouncementRepository;

class AnnouncementService
{
    private $announcementRepository;

    public function __construct(
        AnnouncementRepository $announcementRepository,
    ) {
        $this->announcementRepository = $announcementRepository;
    }

    public function createAnnouncement(
        $title,
        $announcementHtml,
    ) {
        $uid = uniqid(mt_rand(), true);
        $this->announcementRepository->createAnnouncement($uid, $title, $announcementHtml);
    }

    public function editAnnouncement(
        $id,
        $title,
        $announcementHtml,
    ) {

        $this->announcementRepository->editAnnouncement($id, $title, $announcementHtml);
    }

    public function deleteAnnouncement(
        $id,
    ) {

        $this->announcementRepository->deleteAnnouncement($id);
    }

    public function getRecentAnnouncements()
    {
        $eloquentModels = $this->announcementRepository->getRecentAnnouncements();
        return collect($eloquentModels)->map(function ($item) {
            return new AnnouncementsSchema($item);
        })->all();
    }

    public function getAnnouncementById($id)
    {
        $eloquentModel = $this->announcementRepository->getAnnouncementById($id);
        if (isset($eloquentModel)) {
            return new AnnouncementsSchema($eloquentModel);
        }
        return null;
    }
}

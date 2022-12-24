<?php

namespace App\Repositories;

use \ErrorException;
use \Exception;
use App\Models\Database\Announcement\AnnouncementsEloquentModel;

class AnnouncementRepository
{
    public function createAnnouncement(
        $id,
        $title,
        $announcementHtml
    ) {
        try {
            AnnouncementsEloquentModel::create([
                '_id' => $id,
                'title' => $title,
                'announcement_html' => $announcementHtml,
            ]);
        } catch (Exception $ex) {
            throw new ErrorException('Can not create announcement');
        }
    }

    public function editAnnouncement(
        $id,
        $title,
        $announcementHtml
    ) {
        try {
            AnnouncementsEloquentModel::where('_id', $id)->update([
                'title' => $title,
                'announcement_html' => $announcementHtml,
            ]);
        } catch (Exception $ex) {
            throw new ErrorException('Can not edit announcement');
        }
    }

    public function deleteAnnouncement($id)
    {
        try {
            AnnouncementsEloquentModel::where('_id', $id)->delete();
        } catch (Exception $ex) {
            throw new ErrorException('Can not delete announcement');
        }
    }

    public function getRecentAnnouncements()
    {
        return AnnouncementsEloquentModel::orderBy('created_at', 'desc')
            ->take(50)->get();
    }

    public function getAnnouncementById($id)
    {
        return AnnouncementsEloquentModel::where('_id', $id)->first();
    }
}

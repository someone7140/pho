<?php

namespace App\Models\Database\Announcement;

use Jenssegers\Mongodb\Eloquent\Model;

class AnnouncementsEloquentModel extends Model
{
    protected $collection = 'announcements';
    protected $fillable = [
        '_id',
        'title',
        'announcement_html',
    ];
}

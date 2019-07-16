<?php

namespace App\Models\Db;
use App\User;

class Event extends \Moloquent
{
    protected $collection = 'event';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

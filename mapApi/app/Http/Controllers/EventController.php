<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\User;
use App\Models\Event;

class EventController
{
    public function findByUser()
    {
        DB::enableQueryLog();
        $user = User::find(0);
        $aaa = $user->event;
        $event = Event::find(0);
        $bbb = $event->user;
        return DB::getQueryLog();
    }
}

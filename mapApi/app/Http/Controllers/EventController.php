<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Http\Requests\EventRequest;
use App\Models\Db\Event;
use App\Models\Response\CommonResponse;

class EventController
{
    public function create(EventRequest $request)
    {
        $event = new Event;
        $user = Auth::user();
        $event->title = $request->title;
        $event->detail = $request->detail;
        $event->user_id = $user->_id;
        $event->save();
        $response = new CommonResponse();
        $response->status = config('const_http_status.OK_200');
        return $response->return_response();
    }
    public function list(Request $request)
    {
        DB::enableQueryLog();
        $eventList = Event::with('user')->orderBy('updated_at', 'desc')->paginate(5);     
        return DB::getQueryLog();
    }
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

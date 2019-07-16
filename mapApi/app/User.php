<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use App\Models\Db\Event;

class User extends Authenticatable
{
    use Notifiable;

    protected $collection = 'users';

    public function event()
    {
        return $this->hasOne(Event::class);
    }
}

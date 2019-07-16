<?php

namespace App\Models\Db;

class Increment extends \Moloquent
{
    protected $collection = 'increment';

    public function get_user_id()
    {
        return $this->seq + $this->increment('seq');
    }

}

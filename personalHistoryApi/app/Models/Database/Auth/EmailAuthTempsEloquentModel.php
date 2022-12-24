<?php

namespace App\Models\Database\Auth;

use Jenssegers\Mongodb\Eloquent\Model;

class EmailAuthTempsEloquentModel extends Model
{
    protected $collection = 'email_auth_temps';
    protected $fillable = [
        '_id',
        'email',
        'password',
    ];
}

<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasswordReset extends Model
{
    protected $table = 'password_resets';
 
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::orderedUuid();
        });
    }
}

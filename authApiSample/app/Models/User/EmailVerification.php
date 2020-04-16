<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailVerification extends Model
{
    protected $table = 'email_verifications';
 
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

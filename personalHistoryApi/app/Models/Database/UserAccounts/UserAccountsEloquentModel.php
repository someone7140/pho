<?php

namespace App\Models\Database\UserAccounts;

use Jenssegers\Mongodb\Eloquent\Model;

class UserAccountsEloquentModel extends Model
{
    protected $collection = 'user_accounts';
    protected $fillable = [
        '_id',
        'user_id',
        'name',
        'is_account_open',
        'is_admin',
        "occupation",
        'description',
        'icon_image_url',
        'auth_info',
        'external_info',
    ];
}

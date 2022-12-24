<?php

namespace App\Models\Database\History;

use Jenssegers\Mongodb\Eloquent\Model;

class HistoryCategoriesEloquentModel extends Model
{
    protected $collection = 'history_categories';
    protected $fillable = [
        '_id',
        'name',
        'user_account_id',
        'default_setting_flag',
        'default_sort',
    ];
}

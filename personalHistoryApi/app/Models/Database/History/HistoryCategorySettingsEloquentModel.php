<?php

namespace App\Models\Database\History;

use Jenssegers\Mongodb\Eloquent\Model;

class HistoryCategorySettingsEloquentModel extends Model
{
    protected $collection = 'history_category_settings';
    protected $fillable = [
        '_id',
        'user_account_id',
        'category_settings',
        'updated_at',
    ];
}

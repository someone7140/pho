<?php

namespace App\Models\Database\History;

use Jenssegers\Mongodb\Eloquent\Model;

class HistoriesEloquentModel extends Model
{
    protected $collection = 'histories';
    protected $fillable = [
        '_id',
        'user_account_id',
        'category_id',
        "history_records"
    ];
}

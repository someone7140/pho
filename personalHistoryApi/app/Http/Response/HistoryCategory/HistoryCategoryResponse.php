<?php

namespace App\Http\Response\HistoryCategory;

use App\Http\Response\Common\CommonAbstractResponse;

class HistoryCategoryResponse extends CommonAbstractResponse
{
    public function __construct(
        public string $id,
        public string $name,
        public bool $defaultSettingFlag,
        public bool $openFlag,
    ) {
    }
}

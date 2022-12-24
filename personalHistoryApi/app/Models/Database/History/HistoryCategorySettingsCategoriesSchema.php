<?php

namespace App\Models\Database\History;

class HistoryCategorySettingsCategoriesSchema
{
    public function __construct(
        public string $categoryId,
        public bool $openFlag,
    ) {
    }
}

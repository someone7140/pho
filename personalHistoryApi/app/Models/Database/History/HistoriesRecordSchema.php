<?php

namespace App\Models\Database\History;

class HistoriesRecordSchema
{
    public function __construct(
        public string $title,
        public ?string $description,
        public ?string $startDateYyyyMMStr,
        public ?string $endDateYyyyMMStr
    ) {
    }
}

<?php

namespace App\Http\Response\History;

use App\Http\Response\Common\CommonAbstractResponse;

class HistoryResponse extends CommonAbstractResponse
{
    public function __construct(
        public ?string $id,
        public array $historyRecords,
        public string $categoryId,
        public string $categoryName,
        public bool $openFlag,
    ) {
    }
}

<?php

namespace App\Http\Response\History;

use App\Http\Response\Common\CommonAbstractResponse;
use App\Http\Response\UserAccount\OpenUserAccountResponse;

class OpenHistoriesRefResponse extends CommonAbstractResponse
{
    public function __construct(
        public array $histories,
        public OpenUserAccountResponse $user,
    ) {
    }
}

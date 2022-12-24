<?php

namespace App\Http\Response\UserAccountRegister;

use App\Http\Response\Common\CommonAbstractResponse;

class RegisterByTwitterAuthCodeResponse extends CommonAbstractResponse
{
    public function __construct(
        public string $userName,
        public string $twitterToken
    ) {
    }
}

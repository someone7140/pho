<?php

namespace App\Http\Response\UserAccountRegister;

use App\Http\Response\Common\CommonAbstractResponse;

class RegisterByGoogleAuthCodeResponse extends CommonAbstractResponse
{
    public function __construct(
        public string $name,
        public string $googleToken
    ) {
    }
}

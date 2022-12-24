<?php

namespace App\Models\Database\UserAccounts;

class UserAccountsAuthInfoSchema
{
    public function __construct(
        public ?string $email,
        public ?string $password,
        public ?string $gmail,
        public ?string $twitterId
    ) {
    }
}

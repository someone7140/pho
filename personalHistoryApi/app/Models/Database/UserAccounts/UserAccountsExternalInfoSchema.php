<?php

namespace App\Models\Database\UserAccounts;

class UserAccountsExternalInfoSchema
{
    public function __construct(
        public ?string $twitterUserName,
        public ?string $instagramId,
        public ?string $gitHubId
    ) {
    }
}

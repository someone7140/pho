<?php

namespace App\Http\Response\UserAccount;

use App\Http\Response\Common\CommonAbstractResponse;

class UserAccountResponse extends CommonAbstractResponse
{
    public function __construct(
        public string $token,
        public string $userId,
        public string $name,
        public bool $isAccountOpen,
        public bool $isAdmin,
        public string $authMethod,
        public ?string $occupation,
        public ?string $description,
        public ?string $iconImageUrl,
        public ?string $twitterUserName,
        public ?string $instagramId,
        public ?string $gitHubId,
    ) {
    }
}

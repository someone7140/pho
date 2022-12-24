<?php

namespace App\Http\Response\UserAccount;

use App\Http\Response\Common\CommonAbstractResponse;

class OpenUserAccountResponse extends CommonAbstractResponse
{
    public function __construct(
        public string $userId,
        public string $name,
        public ?string $occupation,
        public ?string $description,
        public ?string $iconImageUrl,
        public ?string $twitterUserName,
        public ?string $instagramId,
        public ?string $gitHubId,
        public string $createdAt
    ) {
    }
}

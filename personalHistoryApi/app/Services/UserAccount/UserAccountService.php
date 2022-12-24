<?php

namespace App\Services\UserAccount;

use App\Http\Response\UserAccount\UserAccountResponse;
use App\Models\Database\UserAccounts\UserAccountsSchema;
use App\Repositories\UserAccountRepository;
use App\Services\Common\JwtService;

class UserAccountService
{
    private $jwtService;
    private $userAccountRepository;

    public function __construct(
        JwtService $jwtService,
        UserAccountRepository $userAccountRepository,
    ) {
        $this->jwtService = $jwtService;
        $this->userAccountRepository = $userAccountRepository;
    }

    public function getUserResponseFromUsersSchema(UserAccountsSchema $userSchema)
    {
        $twitterUserName = null;
        $instagramId = null;
        $gitHubId = null;
        if (isset($userSchema)) {
            $twitterUserName = $userSchema->externalInfo->twitterUserName;
            $instagramId = $userSchema->externalInfo->instagramId;
            $gitHubId = $userSchema->externalInfo->gitHubId;
        }
        return new UserAccountResponse(
            $this->getEncodedUserIdToken($userSchema->id, 60 * 60 * 720), // トークンの認証期限は30日とする
            $userSchema->userId,
            $userSchema->name,
            $userSchema->isAccountOpen,
            $userSchema->isAdmin,
            $userSchema->getAuthMethod(),
            $userSchema->occupation,
            $userSchema->description,
            $userSchema->iconImageUrl,
            $twitterUserName,
            $instagramId,
            $gitHubId,
        );
    }

    public function getEncodedUserIdToken($id, $limitTime)
    {
        return $this->jwtService->getEncodedToken(array('id' => $id), $limitTime);
    }

    public function decodeUserIdToken($token)
    {
        $decodeResult = $this->jwtService->getDecodedResult($token);
        return $decodeResult->id;
    }

    public function getOpenUsers()
    {
        $users = $this->userAccountRepository->getOpenUsers();
        return collect($users)->map(function ($item) {
            return new UserAccountsSchema($item);
        })->all();
    }
}

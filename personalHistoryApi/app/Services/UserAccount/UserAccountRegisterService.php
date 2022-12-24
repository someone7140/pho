<?php

namespace App\Services\UserAccount;

use \ErrorException;

use App\Repositories\EmailAuthTempRepository;
use App\Repositories\UserAccountRepository;
use App\Services\Auth\EmailAuthService;
use App\Services\Common\JwtService;
use App\Services\UserAccount\UserAccountService;
use App\Services\UserAccount\UserAccountAuthService;
use App\Services\File\StorageService;

class UserAccountRegisterService
{
    private $emailAuthService;
    private $storageService;
    private $jwtService;
    private $userAccountService;
    private $userAccountAuthService;
    private $emailAuthTempRepository;
    private $userAccountRepository;
    public function __construct(
        EmailAuthService $emailAuthService,
        StorageService $storageService,
        JwtService $jwtService,
        UserAccountService $userAccountService,
        UserAccountAuthService $userAccountAuthService,
        EmailAuthTempRepository $emailAuthTempRepository,
        UserAccountRepository $userAccountRepository,
    ) {
        $this->emailAuthService = $emailAuthService;
        $this->storageService = $storageService;
        $this->jwtService = $jwtService;
        $this->userAccountService = $userAccountService;
        $this->userAccountAuthService = $userAccountAuthService;
        $this->emailAuthTempRepository = $emailAuthTempRepository;
        $this->userAccountRepository = $userAccountRepository;
    }

    public function createUser(
        $userId,
        $name,
        $isAccountOpen,
        $occupation,
        $description,
        $emailToken,
        $googleToken,
        $twitterToken,
        $twitterUserName,
        $instagramId,
        $gitHubId,
        $iconImageFile,
    ) {

        $uid = uniqid(mt_rand(), true);
        $authMethod = '';
        $iconImageUrl = null;

        if (
            !isset($googleToken) &&
            !isset($emailToken) &&
            !isset($twitterToken)
        ) {
            throw new ErrorException('Can not get auth info');
        } else {
            if (isset($iconImageFile)) {
                // 画像のアップロード
                $iconImageUrl = $this->uploadIconImageFile($uid, $iconImageFile);
            }

            if (isset($googleToken)) {
                $authMethod = 'gmail';
                // googleTokenを複合
                $googleAccountInfo = $this->jwtService->getDecodedResult($googleToken);
                // DBに登録
                $this->userAccountRepository->createUser(
                    $uid,
                    $userId,
                    $name,
                    $isAccountOpen,
                    $occupation,
                    $description,
                    $iconImageUrl,
                    null,
                    null,
                    $googleAccountInfo->gmail,
                    null,
                    $twitterUserName,
                    $instagramId,
                    $gitHubId,
                );
            } else if (isset($emailToken)) {
                $authMethod = 'email';
                $authId = $this->jwtService->getDecodedResult($emailToken)->id;
                $emailAuthTemp = $this->emailAuthService->getEmailAuthTempById($authId);
                if (!isset($emailAuthTemp)) {
                    throw new ErrorException('Can not get email auth info');
                }
                // DBに登録
                $this->userAccountRepository->createUser(
                    $uid,
                    $userId,
                    $name,
                    $isAccountOpen,
                    $occupation,
                    $description,
                    $iconImageUrl,
                    $emailAuthTemp->email,
                    $emailAuthTemp->password,
                    null,
                    null,
                    $twitterUserName,
                    $instagramId,
                    $gitHubId,
                );
                // 該当のemail一時データの削除
                $this->emailAuthTempRepository->deleteEmailAuthTempByEmail($emailAuthTemp->email);
            } else if (isset($twitterToken)) {
                $authMethod = 'twitter';
                $twitterId = $this->jwtService->getDecodedResult($twitterToken)->id;
                // DBに登録
                $this->userAccountRepository->createUser(
                    $uid,
                    $userId,
                    $name,
                    $isAccountOpen,
                    $occupation,
                    $description,
                    $iconImageUrl,
                    null,
                    null,
                    null,
                    $twitterId,
                    $twitterUserName,
                    $instagramId,
                    $gitHubId,
                );
            }
        }

        // tokenの生成
        $token = $this->userAccountService->getEncodedUserIdToken($uid, 60 * 60 * 24);
        return array($token, $authMethod, $iconImageUrl);
    }

    public function editUser(
        $id,
        $userId,
        $name,
        $isAccountOpen,
        $occupation,
        $description,
        $twitterUserName,
        $instagramId,
        $gitHubId,
        $iconImageFile,
    ) {
        // 登録済みのユーザ情報を取得
        $userAccount = $this->userAccountAuthService->getUserById($id);

        if (!isset($userAccount)) {
            throw new ErrorException('Can not get user info');
        } else {
            $iconImageUrl = $userAccount->iconImageUrl;
            if (isset($iconImageFile)) {
                // 画像のアップロード
                $iconImageUrl = $this->uploadIconImageFile($id, $iconImageFile);
            }

            // DBに登録
            $this->userAccountRepository->editUser(
                $id,
                $userId,
                $name,
                $isAccountOpen,
                $occupation,
                $description,
                $iconImageUrl,
                $twitterUserName,
                $instagramId,
                $gitHubId,
            );
        }

        // tokenの生成
        $token = $this->userAccountService->getEncodedUserIdToken($id, 60 * 60 * 24);
        return array($token, $userAccount->getAuthMethod(), $iconImageUrl);
    }

    private function uploadIconImageFile($id, $file)
    {
        $ext = $file->extension();
        return $this->storageService->uploadFile(
            $file,
            'icon_image',
            $id . "." . $ext
        );
    }

    public function isRegisteredUserByUserId(
        $userId,
    ) {
        $user = $this->userAccountRepository->getUserByUserId($userId);
        return isset($user);
    }

    public function isRegisteredUserByGmail(
        $gmail,
    ) {
        $user = $this->userAccountRepository->getUserByGmail($gmail);
        return isset($user);
    }

    public function isRegisteredUserByTwitterId(
        $twitterId,
    ) {
        $user = $this->userAccountRepository->getUserByTwitterId($twitterId);
        return isset($user);
    }

    public function isRegisteredUserByEmail(
        $email,
    ) {
        $user = $this->userAccountRepository->getUserByEmail($email);
        return isset($user);
    }
}

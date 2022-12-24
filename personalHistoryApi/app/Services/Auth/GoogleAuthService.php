<?php

namespace App\Services\Auth;

use \ErrorException;
use Google\Client;

use App\Http\Response\UserAccountRegister\RegisterByGoogleAuthCodeResponse;
use App\Services\Common\JwtService;

class GoogleAuthService
{
    private $jwtService;
    public function __construct(
        JwtService $jwtService,
    ) {
        $this->jwtService = $jwtService;
    }

    public function getUserInfoFromAuthCode($authCode)
    {
        // 認可コードの認証
        $googleClient = new Client();
        $googleClient->setAccessType('offline');
        $googleClient->setAuthConfig(base_path() . '/' . env('GOOGLE_SECRETS_PATH'));
        $googleClient->setRedirectUri(env("FRONT_END_URL"));
        $token = $googleClient->fetchAccessTokenWithAuthCode($authCode);
        // ユーザ情報の取得
        $userInfo = $googleClient->verifyIdToken($token['id_token']);
        if (!$userInfo) {
            throw new ErrorException('Can not get user info');
        }
        return array('gmail' => $userInfo['email'], 'name' => $userInfo['name']);
    }

    public function getRegisterByGoogleAuthCodeResponse($authUser)
    {
        $token =  $this->jwtService->getEncodedToken($authUser, 60 * 60 * 24);
        return new RegisterByGoogleAuthCodeResponse($authUser['name'], $token);
    }
}

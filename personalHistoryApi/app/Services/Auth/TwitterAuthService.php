<?php

namespace App\Services\Auth;

use App\Http\Response\UserAccountRegister\RegisterByTwitterAuthCodeResponse;
use App\Services\Common\JwtService;

class TwitterAuthService
{
    private $jwtService;
    public function __construct(
        JwtService $jwtService,
    ) {
        $this->jwtService = $jwtService;
    }

    public function getUserInfoFromCode($authCode, $codeVerifier, $redirectPath)
    {
        // access_tokenの取得
        $chGetToken = curl_init();
        curl_setopt($chGetToken, CURLOPT_POST, true);
        curl_setopt($chGetToken, CURLOPT_URL, 'https://api.twitter.com/2/oauth2/token');
        curl_setopt($chGetToken, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chGetToken, CURLOPT_USERPWD, sprintf('%s:%s', env('TWITTER_CLIENT_ID'), env('TWITTER_CLIENT_SECRET')));
        curl_setopt($chGetToken, CURLOPT_POSTFIELDS, http_build_query(array(
            'code' => $authCode,
            'grant_type' => 'authorization_code',
            'client_id' => env('TWITTER_CLIENT_ID'),
            'redirect_uri' => env('FRONT_END_URL') . $redirectPath,
            'code_verifier' => $codeVerifier,
        )));
        $accessToken = json_decode(curl_exec($chGetToken))->access_token;

        // access_tokenを使ってログインユーザの情報を取得
        $chUserToken = curl_init();
        curl_setopt($chUserToken, CURLOPT_URL, 'https://api.twitter.com/2/users/me');
        curl_setopt($chUserToken, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chUserToken, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer " . $accessToken));
        $userInfo = json_decode(curl_exec($chUserToken))->data;
        return $userInfo;
    }

    public function getRegisterByTwitterAuthCodeResponse($authUser)
    {
        $token =  $this->jwtService->getEncodedToken((array)$authUser, 60 * 60 * 24);
        return new RegisterByTwitterAuthCodeResponse($authUser->username, $token);
    }
}

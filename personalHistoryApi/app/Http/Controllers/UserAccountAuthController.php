<?php

namespace App\Http\Controllers;

use \Exception;

use Illuminate\Http\Response;

use App\Http\Requests\UserAccountAuth\ChangePasswordRequest;
use App\Http\Requests\UserAccountAuth\LoginByEmailRequest;
use App\Http\Requests\UserAccountAuth\LoginByGoogleAuthCodeRequest;
use App\Http\Requests\UserAccountAuth\LoginByTwitterAuthCodeRequest;
use App\Services\Auth\GoogleAuthService;
use App\Services\Auth\TwitterAuthService;
use App\Services\UserAccount\UserAccountService;
use App\Services\UserAccount\UserAccountAuthService;

class UserAccountAuthController extends Controller
{

    private $googleAuthService;
    private $userAccountService;
    private $userAccountAuthService;
    private $twitterAuthService;
    public function __construct(
        GoogleAuthService $googleAuthService,
        TwitterAuthService $twitterAuthService,
        UserAccountService $userAccountService,
        UserAccountAuthService $userAccountAuthService,
    ) {
        $this->googleAuthService = $googleAuthService;
        $this->twitterAuthService = $twitterAuthService;
        $this->userAccountService = $userAccountService;
        $this->userAccountAuthService = $userAccountAuthService;
    }

    // googleの認可コードでのログイン
    public function loginByGoogleAuthCode(LoginByGoogleAuthCodeRequest $request)
    {
        $authUserResult = array();
        try {
            // 認可コードから認証ユーザ情報を取得
            $authUserResult = $this->googleAuthService->getUserInfoFromAuthCode($request->authCode);
        } catch (Exception $ex) {
            return response()->json(
                [
                    'message' =>  $ex->getMessage()
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }
        // gmailからユーザ取得
        $userAccount = $this->userAccountAuthService->getUserByGmail($authUserResult['gmail']);
        if (!isset($userAccount)) {
            return response()->json(
                [
                    'message' =>  'Not found user'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        return response()->json(
            $this->userAccountService->getUserResponseFromUsersSchema($userAccount),
            Response::HTTP_OK
        );
    }

    // Twitterの認可コードでログイン
    public function loginByTwitterAuthCode(LoginByTwitterAuthCodeRequest $request)
    {
        // ユーザ情報の取得
        try {
            $userInfo = $this->twitterAuthService->getUserInfoFromCode(
                $request->authCode,
                $request->codeVerifier,
                '/auth/twitter_redirect_for_login',
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                    'message' =>  $ex->getMessage()
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }
        // ユーザ取得
        $userAccount = $this->userAccountAuthService->getUserByTwitterId($userInfo->id);
        if (!isset($userAccount)) {
            return response()->json(
                [
                    'message' =>  'Not found user'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json(
            $this->userAccountService->getUserResponseFromUsersSchema($userAccount),
            Response::HTTP_OK
        );
    }

    // emailでログイン
    public function loginByEmail(LoginByEmailRequest $request)
    {
        // ユーザ取得
        $userAccount = $this->userAccountAuthService->getUserByEmail($request->email);
        if (!isset($userAccount)) {
            return response()->json(
                [
                    'message' =>  'Not found user'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        // パスワード比較
        if (password_verify($request->password, $userAccount->authInfo->password)) {
            return response()->json(
                $this->userAccountService->getUserResponseFromUsersSchema($userAccount),
                Response::HTTP_OK
            );
        } else {
            return response()->json(
                [
                    'message' =>  'Invalid password'
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }

    // パスワード変更
    public function changePassword(ChangePasswordRequest $request)
    {
        // アカウントのID取得
        $id = $request->userAccountId;
        // アカウントのIDによるユーザ取得
        $userAccount = $this->userAccountAuthService->getUserById($id);
        // メールで登録されたアカウントではない
        if (!isset($userAccount) || $userAccount->getAuthMethod() != "email") {
            return response()->json(
                [
                    'message' => 'Not email user'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        // 変更処理
        try {
            $this->userAccountAuthService->changePassword($id, $request->password);
            return response()->json(
                null,
                Response::HTTP_OK
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }
}

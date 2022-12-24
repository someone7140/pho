<?php

namespace App\Http\Controllers;

use \Exception;

use Illuminate\Http\Response;

use App\Http\Response\UserAccount\UserAccountResponse;
use App\Http\Requests\UserAccountRegister\AuthEmailRegisteredRequest;
use App\Http\Requests\UserAccountRegister\RegisterByEmailRequest;
use App\Http\Requests\UserAccountRegister\RegisterByGoogleAuthCodeRequest;
use App\Http\Requests\UserAccountRegister\RegisterByTwitterAuthCodeRequest;
use App\Http\Requests\UserAccountRegister\RegisterUserAccountRequest;
use App\Services\Auth\EmailAuthService;
use App\Services\Auth\GoogleAuthService;
use App\Services\Auth\TwitterAuthService;
use App\Services\Common\JwtService;
use App\Services\UserAccount\UserAccountAuthService;
use App\Services\UserAccount\UserAccountRegisterService;

class UserAccountRegisterController extends Controller
{

    private $googleAuthService;
    private $emailAuthService;
    private $jwtService;
    private $twitterAuthService;
    private $userAccountAuthService;
    private $userAccountRegisterService;
    public function __construct(
        GoogleAuthService $googleAuthService,
        EmailAuthService $emailAuthService,
        JwtService $jwtService,
        TwitterAuthService $twitterAuthService,
        UserAccountAuthService $userAccountAuthService,
        UserAccountRegisterService $userAccountRegisterService,
    ) {
        $this->googleAuthService = $googleAuthService;
        $this->emailAuthService = $emailAuthService;
        $this->jwtService = $jwtService;
        $this->twitterAuthService = $twitterAuthService;
        $this->userAccountAuthService = $userAccountAuthService;
        $this->userAccountRegisterService = $userAccountRegisterService;
    }

    // ユーザ登録時のgoogleの認可コード認証
    public function registerByGoogleAuthCode(RegisterByGoogleAuthCodeRequest $request)
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
        // すでに登録済みのgmailでないか
        $isRegisteredGmail = $this->userAccountRegisterService->isRegisteredUserByGmail($authUserResult['gmail']);
        if ($isRegisteredGmail) {
            return response()->json(
                [
                    'message' =>  'Already registered gmail'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        return response()->json(
            $this->googleAuthService->getRegisterByGoogleAuthCodeResponse($authUserResult),
            Response::HTTP_OK
        );
    }

    // ユーザ登録時のTwitter認証
    public function registerByTwitterAuthCode(RegisterByTwitterAuthCodeRequest $request)
    {
        // ユーザ情報の取得
        try {
            $userInfo = $this->twitterAuthService->getUserInfoFromCode(
                $request->authCode,
                $request->codeVerifier,
                '/auth/twitter_redirect_for_register',
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                    'message' =>  $ex->getMessage()
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }
        // すでに登録済みのidではないか
        $isRegisteredTwitterId = $this->userAccountRegisterService->isRegisteredUserByTwitterId($userInfo->id);
        if ($isRegisteredTwitterId) {
            return response()->json(
                [
                    'message' =>  'Already registered twitter account'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return response()->json(
            $this->twitterAuthService->getRegisterByTwitterAuthCodeResponse($userInfo),
            Response::HTTP_OK
        );
    }

    // ユーザ登録時のメール認証
    public function registerByEmail(RegisterByEmailRequest $request)
    {
        // すでに登録済みのメールアドレスでないか
        $isRegisteredEmail = $this->userAccountRegisterService->isRegisteredUserByEmail($request->email);
        if ($isRegisteredEmail) {
            return response()->json(
                [
                    'message' => 'Already registered email'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        try {
            // 認証メール送信処理
            $this->emailAuthService->authEmailSend($request->email, $request->password);
            return response()->json(
                null,
                Response::HTTP_OK
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                    'message' =>  $ex->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    // 登録したメールの認証
    public function authEmailRegistered(AuthEmailRegisteredRequest $request)
    {
        // 認証チェック
        $isAuthCheckOk = $this->emailAuthService->authRegisteredEmail($request->id, $request->password);
        if (!$isAuthCheckOk) {
            return response()->json(
                [
                    'message' => 'Failed email authorize'
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }
        // idをトークンにして返す
        $token = $this->jwtService->getEncodedToken(array('id' => $request->id), 60 * 60 * 24);
        return response()->json(
            [
                'emailToken' => $token
            ],
            Response::HTTP_OK
        );
    }

    // ユーザ登録
    public function registerUserAccount(RegisterUserAccountRequest $request)
    {
        // ユーザIDの重複チェック
        $isRegisteredUserId = $this->userAccountRegisterService->isRegisteredUserByUserId($request->userId);
        if ($isRegisteredUserId) {
            return response()->json(
                [
                    'message' => 'Already registered userId'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        // 認証情報のチェック
        if (
            !isset($request->googleToken) &&
            !isset($request->emailToken) &&
            !isset($request->twitterToken)
        ) {
            return response()->json(
                ['message' => 'Token is required'],
                Response::HTTP_BAD_REQUEST
            );
        }

        // 登録処理
        $isAccountOpen = $request->isAccountOpen == "true";
        try {
            list($token, $authMethod, $iconImageUrl) = $this->userAccountRegisterService->createUser(
                $request->userId,
                $request->name,
                $isAccountOpen,
                $request->occupation,
                $request->description,
                $request->emailToken,
                $request->googleToken,
                $request->twitterToken,
                $request->twitterUserName,
                $request->instagramId,
                $request->gitHubId,
                $request->file('iconImage')
            );
            return response()->json(
                new UserAccountResponse(
                    $token,
                    $request->userId,
                    $request->name,
                    $isAccountOpen,
                    false,
                    $authMethod,
                    $request->occupation,
                    $request->description,
                    $iconImageUrl,
                    $request->twitterUserName,
                    $request->instagramId,
                    $request->gitHubId,
                ),
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

    // ユーザ編集
    public function editUserAccount(RegisterUserAccountRequest $request)
    {
        // アカウントのID取得
        $id = $request->userAccountId;
        // ユーザIDによるユーザ取得
        $userAccount = $this->userAccountAuthService->getUserByUserId($request->userId);
        // userIdが他のアカウントに紐づいている
        if (isset($userAccount) && $id != $userAccount->id) {
            return response()->json(
                [
                    'message' => 'Already registered userId'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        // 編集処理
        $isAccountOpen = $request->isAccountOpen == "true";
        try {
            list($token, $authMethod, $iconImageUrl) = $this->userAccountRegisterService->editUser(
                $id,
                $request->userId,
                $request->name,
                $isAccountOpen,
                $request->occupation,
                $request->description,
                $request->twitterUserName,
                $request->instagramId,
                $request->gitHubId,
                $request->file('iconImage')
            );
            return response()->json(
                new UserAccountResponse(
                    $token,
                    $request->userId,
                    $request->name,
                    $isAccountOpen,
                    false,
                    $authMethod,
                    $request->occupation,
                    $request->description,
                    $iconImageUrl,
                    $request->twitterUserName,
                    $request->instagramId,
                    $request->gitHubId,
                ),
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

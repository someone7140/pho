<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Response\UserAccount\OpenUserAccountResponse;

use App\Services\UserAccount\UserAccountService;
use App\Services\UserAccount\UserAccountAuthService;

class UserAccountRefController extends Controller
{
    private $userAccountService;
    private $userAccountAuthService;
    public function __construct(
        UserAccountService $userAccountService,
        UserAccountAuthService $userAccountAuthService,
    ) {
        $this->userAccountService = $userAccountService;
        $this->userAccountAuthService = $userAccountAuthService;
    }

    // 自身のユーザ情報取得
    public function getMyUserInfo(Request $request)
    {
        // idからユーザ取得
        $userAccount = $this->userAccountAuthService->getUserById($request->userAccountId);
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

    // 公開ユーザの一覧
    public function getOpenUserList()
    {
        $userAccountList = $this->userAccountService->getOpenUsers();
        return response()->json(
            collect($userAccountList)->map(function ($item) {
                return new OpenUserAccountResponse(
                    $item->userId,
                    $item->name,
                    $item->occupation,
                    $item->description,
                    $item->iconImageUrl,
                    $item->externalInfo->twitterUserName,
                    $item->externalInfo->instagramId,
                    $item->externalInfo->gitHubId,
                    $item->createdAt
                );
            })->all(),
            Response::HTTP_OK
        );
    }
}

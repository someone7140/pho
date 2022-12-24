<?php

namespace App\Http\Controllers;

use \Exception;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Response\History\HistoryResponse;
use App\Http\Response\History\OpenHistoriesRefResponse;
use App\Http\Response\UserAccount\OpenUserAccountResponse;
use App\Http\Requests\History\GetHistoryInfoByUserRequest;
use App\Http\Requests\History\HistoryRegisterRequest;

use App\Services\History\HistoryCategoryService;
use App\Services\History\HistoryService;
use App\Services\UserAccount\UserAccountAuthService;

class HistoryController extends Controller
{
    private $historyCategoryService;
    private $historyService;
    private $userAccountAuthService;

    public function __construct(
        HistoryCategoryService $historyCategoryService,
        HistoryService $historyService,
        UserAccountAuthService $userAccountAuthService,
    ) {
        $this->historyCategoryService = $historyCategoryService;
        $this->historyService = $historyService;
        $this->userAccountAuthService = $userAccountAuthService;
    }

    // 経歴の登録
    public function registerHistory(HistoryRegisterRequest $request)
    {
        try {
            $this->historyService->registerHistory(
                $request->id,
                $request->userAccountId,
                $request->categoryId,
                $request->historyRecords,
            );
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

    // 自分の経歴を取得
    public function getOwnHistories(Request $request)
    {
        try {
            // カテゴリーの取得
            $categories = $this->historyCategoryService->getAllCategoriesByUserAccountId($request->userAccountId);
            if (count($categories) == 0) {
                return response()->json(
                    [],
                    Response::HTTP_OK
                );
            }
            // カテゴリーIDから経歴を取得
            $histories = $this->historyService->getHistoriesByCategoryId(
                $request->userAccountId,
                collect($categories)->map(function ($item) {
                    return $item->id;
                }),
            );
            // カテゴリー情報と経歴を結合
            return response()->json(
                collect($categories)->map(function ($category) use ($histories) {
                    $history = collect($histories)->first(function ($item) use ($category) {
                        return $item->categoryId == $category->id;
                    });
                    if (isset($history)) {
                        return new HistoryResponse(
                            $history->id,
                            $history->historyRecords,
                            $category->id,
                            $category->name,
                            $category->openFlag,
                        );
                    } else {
                        return new HistoryResponse(
                            null,
                            [],
                            $category->id,
                            $category->name,
                            $category->openFlag,
                        );
                    }
                }),
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

    // ユーザIDを指定して経歴を取得
    public function getHistoryInfoByUserId(GetHistoryInfoByUserRequest $request)
    {
        try {
            // ユーザ情報の取得
            $userInfo = $this->userAccountAuthService->getUserByUserId($request->userId);
            if (!isset($userInfo) || !$userInfo->isAccountOpen) {
                return response()->json(
                    null,
                    Response::HTTP_NOT_FOUND
                );
            }
            // 経歴を取得
            $histories = $this->historyService->getHistoriesResponseByUserAccountId(
                $userInfo->id,
            );
            // カテゴリー情報と経歴を結合
            return response()->json(
                new OpenHistoriesRefResponse(
                    $histories,
                    new OpenUserAccountResponse(
                        $userInfo->userId,
                        $userInfo->name,
                        $userInfo->occupation,
                        $userInfo->description,
                        $userInfo->iconImageUrl,
                        $userInfo->externalInfo->twitterUserName,
                        $userInfo->externalInfo->instagramId,
                        $userInfo->externalInfo->gitHubId,
                        $userInfo->createdAt->format('Y-m-d H:i:s'),
                    )
                ),
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
}

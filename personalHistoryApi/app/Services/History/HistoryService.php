<?php

namespace App\Services\History;

use App\Models\Database\History\HistoriesSchema;
use App\Repositories\HistoryRepository;
use App\Services\History\HistoryCategoryService;

use App\Http\Response\History\HistoryResponse;

class HistoryService
{

    private $historyRepository;
    private $historyCategoryService;

    public function __construct(
        HistoryRepository $historyRepository,
        HistoryCategoryService $historyCategoryService,
    ) {
        $this->historyRepository = $historyRepository;
        $this->historyCategoryService = $historyCategoryService;
    }

    // 経歴登録
    public function registerHistory($id, $userAccountId, $categoryId, $historyRecords)
    {
        if (!isset($id)) {
            $uid = uniqid(mt_rand(), true);
            $this->historyRepository->addHistory($uid, $userAccountId, $categoryId, $historyRecords);
        } else {
            $this->historyRepository->updateHistory($id, $userAccountId, $categoryId, $historyRecords);
        }
    }

    // 複数のカテゴリーID指定で経歴取得
    public function getHistoriesByCategoryId($userAccountId, $categoryIds)
    {
        $eloquentModels = $this->historyRepository->getHistoriesByCategoryIds($userAccountId, $categoryIds);
        return collect($eloquentModels)->map(function ($item) {
            return new HistoriesSchema($item);
        });
    }

    // ユーザのアカウントIDで経歴を取得
    public function getHistoriesResponseByUserAccountId($userAccountId)
    {
        $categories = $this->historyCategoryService->getAllCategoriesByUserAccountId($userAccountId);
        $histories = $this->getHistoriesByCategoryId(
            $userAccountId,
            collect($categories)->filter(function ($item) {
                return $item->openFlag;
            })->map(function ($item) {
                return $item->id;
            })->all()
        );
        return collect($histories)->map(function ($history) use ($categories) {
            // カテゴリーの取得
            $category = collect($categories)->first(function ($category) use ($history) {
                return $category->id == $history->categoryId;
            });
            if (!isset($category)) {
                return null;
            }
            return new HistoryResponse(
                $history->id,
                $history->historyRecords,
                $category->id,
                $category->name,
                $category->openFlag,
            );
        })->filter(function ($item) {
            return isset($item);
        })->all();
    }
}

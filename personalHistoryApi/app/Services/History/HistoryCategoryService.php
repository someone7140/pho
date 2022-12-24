<?php

namespace App\Services\History;

use App\Http\Response\HistoryCategory\HistoryCategoryResponse;
use App\Models\Database\History\HistoryCategoriesSchema;
use App\Models\Database\History\HistoryCategorySettingsSchema;
use App\Models\Database\History\HistoryCategorySettingsCategoriesSchema;
use App\Repositories\HistoryRepository;
use App\Repositories\HistoryCategoryRepository;
use App\Repositories\HistoryCategorySettingRepository;

class HistoryCategoryService
{
    private $historyRepository;
    private $historyCategoryRepository;
    private $historyCategorySettingRepository;
    public function __construct(
        HistoryRepository $historyRepository,
        HistoryCategoryRepository $historyCategoryRepository,
        HistoryCategorySettingRepository $historyCategorySettingRepository,
    ) {
        $this->historyRepository = $historyRepository;
        $this->historyCategoryRepository = $historyCategoryRepository;
        $this->historyCategorySettingRepository = $historyCategorySettingRepository;
    }

    // カテゴリー登録
    public function registerCategory($userAccountId, $categories, $deleteCategoryIds)
    {

        // 登録するカテゴリー設定
        $registerCategorySettings = [];
        foreach ($categories as $category) {
            // IDが無かったらDB登録
            if (!isset($category['id'])) {
                $categoryId = uniqid(mt_rand(), true);
                $this->historyCategoryRepository->createCategory($categoryId, $category['name'], $userAccountId);
                array_push($registerCategorySettings, new HistoryCategorySettingsCategoriesSchema(
                    $categoryId,
                    $category['openFlag'],
                ));
            } else {
                array_push($registerCategorySettings, new HistoryCategorySettingsCategoriesSchema(
                    $category['id'],
                    $category['openFlag'],
                ));
            }
        }

        // カテゴリーの削除
        if (isset($deleteCategoryIds) && count($deleteCategoryIds) > 0) {
            $this->historyCategoryRepository->deleteCategories($deleteCategoryIds, $userAccountId);
            // 経歴の削除
            $this->historyRepository->deleteHistoriesByCategoryIds($deleteCategoryIds, $userAccountId);
        }

        // アカウントIDでDBからカテゴリー情報取得
        $categoriesEloquentModel = $this->historyCategoryRepository->getCategoriesByUserAccountId($userAccountId);
        $categoriesFromDb = collect($categoriesEloquentModel)->map(function ($item) {
            return new HistoryCategoriesSchema($item);
        })->all();
        // カテゴリー名の更新
        foreach ($categories as $category) {
            if (array_key_exists('id', $category) && array_key_exists('name', $category)) {
                // DBにあるユーザ登録カテゴリーか
                $registeredUserCategory = collect($categoriesFromDb)->first(function ($item) use ($category) {
                    return !$item->defaultSettingFlag && $category['id'] == $item->id;
                });
                if (isset($registeredUserCategory)) {
                    $this->historyCategoryRepository->updateCategory($category['id'], $userAccountId, $category['name']);
                }
            }
        }

        // ユーザIDでカテゴリー設定を取得
        $settingFromDbEloquent = $this->historyCategorySettingRepository->getCategorySettingByUserAccountId($userAccountId);
        $settingFromDb = isset($settingFromDbEloquent) ?
            new HistoryCategorySettingsSchema($settingFromDbEloquent) : null;
        // DBに登録されているカテゴリーIDで登録する設定を抽出
        $registerCategorySettingsChecked = collect($registerCategorySettings)->filter(function ($registerSetting) use ($categoriesFromDb) {
            return collect($categoriesFromDb)->contains(function ($categoryFromDb) use ($registerSetting) {
                return $categoryFromDb->id == $registerSetting->categoryId;
            });
        })->all();
        if (isset($settingFromDb)) {
            // カテゴリー設定をupdate
            $this->historyCategorySettingRepository->updateCategorySetting(
                $settingFromDb->id,
                $userAccountId,
                $registerCategorySettingsChecked,
            );
        } else {
            // カテゴリー設定を追加
            $uid = uniqid(mt_rand(), true);
            $this->historyCategorySettingRepository->createCategorySetting(
                $uid,
                $userAccountId,
                $registerCategorySettingsChecked,
            );
        }
    }

    // 全登録カテゴリーの取得
    public function getAllCategoriesByUserAccountId($userAccountId)
    {
        // カテゴリーの取得
        $categoriesEloquentModel = $this->historyCategoryRepository->getCategoriesByUserAccountId($userAccountId);
        $categoriesFromDb = collect($categoriesEloquentModel)->map(function ($item) {
            return new HistoryCategoriesSchema($item);
        })->all();
        // カテゴリー設定を取得
        $settingFromDbEloquent = $this->historyCategorySettingRepository->getCategorySettingByUserAccountId($userAccountId);
        $settingFromDb = isset($settingFromDbEloquent) ?
            new HistoryCategorySettingsSchema($settingFromDbEloquent) : null;

        if (isset($settingFromDb)) {
            $response = [];
            // 設定から取得して追加
            foreach ($settingFromDb->categorySettings as $setting) {
                // カテゴリーの登録にあるか
                $registeredCategory = collect($categoriesFromDb)->first(function ($item) use ($setting) {
                    return $setting->categoryId == $item->id;
                });
                if (isset($registeredCategory)) {
                    array_push($response, new HistoryCategoryResponse(
                        $setting->categoryId,
                        $registeredCategory->name,
                        $registeredCategory->defaultSettingFlag,
                        $setting->openFlag,
                    ));
                }
            }

            // 設定にないカテゴリーを追加
            foreach ($categoriesFromDb as $category) {
                // カテゴリー設定にあるか
                $isSettingRegistered = collect($settingFromDb->categorySettings)->contains(function ($setting) use ($category) {
                    return $setting->categoryId == $category->id;
                });
                if (!$isSettingRegistered) {
                    array_push($response, new HistoryCategoryResponse(
                        $category->id,
                        $category->name,
                        $category->defaultSettingFlag,
                        false,
                    ));
                }
            }

            return $response;
        } else {
            // 初期設定を返す
            return collect($categoriesFromDb)->map(function ($item) {
                return new HistoryCategoryResponse(
                    $item->id,
                    $item->name,
                    $item->defaultSettingFlag,
                    false,
                );
            })->all();
        }
    }
}

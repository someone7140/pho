<?php

namespace App\Repositories;

use \ErrorException;
use \Exception;
use App\Models\Database\History\HistoryCategorySettingsEloquentModel;

class HistoryCategorySettingRepository
{

    public function createCategorySetting(
        $id,
        $userAccountId,
        $categorySettings
    ) {
        try {
            HistoryCategorySettingsEloquentModel::create([
                '_id' => $id,
                'user_account_id' => $userAccountId,
                'category_settings' => $this->getRegisterArrayFromSettingSchema($categorySettings)
            ]);
        } catch (Exception $ex) {
            throw new ErrorException('Can not create history_category_settings');
        }
    }

    public function updateCategorySetting(
        $id,
        $userAccountId,
        $categorySettings
    ) {
        try {
            HistoryCategorySettingsEloquentModel::where('_id', $id)
                ->where('user_account_id', $userAccountId)
                ->update([
                    'category_settings' => $this->getRegisterArrayFromSettingSchema($categorySettings),
                ]);
        } catch (Exception $ex) {
            throw new ErrorException('Can not create history_category_settings');
        }
    }

    private function getRegisterArrayFromSettingSchema($categorySettings)
    {
        return collect($categorySettings)->map(function ($item) {
            return [
                'category_id' => $item->categoryId,
                'open_flag' => $item->openFlag,
            ];
        })->all();
    }

    public function getCategorySettingByUserAccountId($userAccountId)
    {
        return HistoryCategorySettingsEloquentModel::where('user_account_id', $userAccountId)->first();;
    }
}

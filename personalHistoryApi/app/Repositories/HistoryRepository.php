<?php

namespace App\Repositories;

use \ErrorException;
use \Exception;
use App\Models\Database\History\HistoriesEloquentModel;

class HistoryRepository
{

    public function addHistory(
        $id,
        $userAccountId,
        $categoryId,
        $historyRecords
    ) {
        try {
            $aaa = $this->getRegisterArrayFromHistoryRecords($historyRecords);
            HistoriesEloquentModel::create([
                '_id' => $id,
                'user_account_id' => $userAccountId,
                'category_id' => $categoryId,
                'history_records' => $this->getRegisterArrayFromHistoryRecords($historyRecords),
            ]);
        } catch (Exception $ex) {
            throw new ErrorException('Can not add histories');
        }
    }

    public function updateHistory(
        $id,
        $userAccountId,
        $categoryId,
        $historyRecords
    ) {
        try {
            HistoriesEloquentModel::where('_id', $id)
                ->where('user_account_id', $userAccountId)
                ->where('category_id', $categoryId)
                ->update([
                    'history_records' => $this->getRegisterArrayFromHistoryRecords($historyRecords),
                ]);
        } catch (Exception $ex) {
            throw new ErrorException('Can not update histories');
        }
    }

    public function deleteHistoriesByCategoryIds(
        $categoryIds,
        $userAccountId,
    ) {
        try {
            HistoriesEloquentModel::where('user_account_id', $userAccountId)
                ->whereIn('category_id', $categoryIds)
                ->delete();
        } catch (Exception $ex) {
            throw new ErrorException('Can not add histories');
        }
    }

    public function getHistoriesByCategoryIds(
        $userAccountId,
        $categoryIds
    ) {
        try {
            return HistoriesEloquentModel::where('user_account_id', $userAccountId)
                ->whereIn('category_id', $categoryIds)->get();
        } catch (Exception $ex) {
            throw new ErrorException('Can not get histories');
        }
    }

    private function getRegisterArrayFromHistoryRecords($historyRecords)
    {
        return collect($historyRecords)->map(function ($item) {
            return [
                'title' => $item['title'],
                'description' => array_key_exists('description', $item) ? $item['description'] : null,
                'start_date_yyyymm_str' => array_key_exists('startDateYyyyMMStr', $item) ? $item['startDateYyyyMMStr'] : null,
                'end_date_yyyymm_str' => array_key_exists('endDateYyyyMMStr', $item) ? $item['endDateYyyyMMStr'] : null,
            ];
        })->all();
    }
}

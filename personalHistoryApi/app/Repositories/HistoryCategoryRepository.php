<?php

namespace App\Repositories;

use \ErrorException;
use \Exception;
use App\Models\Database\History\HistoryCategoriesEloquentModel;

class HistoryCategoryRepository
{

    protected $ALL_USER_ACCOUNT_ID = 'ALL_USER';
    protected $USER_CREATE_CATEGORY_SORT = 9999;

    public function createCategory(
        $id,
        $name,
        $userAccountId,
    ) {
        try {
            HistoryCategoriesEloquentModel::create([
                '_id' => $id,
                'name' => $name,
                'user_account_id' => $userAccountId,
                'default_setting_flag' => false,
                'default_sort' => $this->USER_CREATE_CATEGORY_SORT,
            ]);
        } catch (Exception $ex) {
            throw new ErrorException('Can not create history_categories');
        }
    }

    public function updateCategory(
        $id,
        $userAccountId,
        $name,
    ) {
        HistoryCategoriesEloquentModel::where('_id', $id)
            ->where('user_account_id', $userAccountId)
            ->update([
                'name' => $name,
            ]);
    }

    public function deleteCategories(
        $ids,
        $userAccountId,
    ) {
        HistoryCategoriesEloquentModel::whereIn('_id', $ids)
            ->where('user_account_id', $userAccountId)
            ->delete();
    }

    public function getCategoriesByUserAccountId(
        $userAccountId,
    ) {
        $allUserAccountId = $this->ALL_USER_ACCOUNT_ID;
        return HistoryCategoriesEloquentModel::where(function ($query) use ($userAccountId, $allUserAccountId) {
            $query->where('user_account_id', $userAccountId)
                ->orWhere('user_account_id', $allUserAccountId);
        })->orderBy('default_sort')->get();
    }
}

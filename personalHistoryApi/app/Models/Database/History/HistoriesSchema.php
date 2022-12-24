<?php

namespace App\Models\Database\History;

class HistoriesSchema
{
    public string $id;
    public string $userAccountId;
    public string $categoryId;
    public array $historyRecords;

    public function __construct(
        $eloquentModel,
    ) {
        $this->id = $eloquentModel->id;
        $this->userAccountId = $eloquentModel->user_account_id;
        $this->categoryId = $eloquentModel->category_id;
        $historyRecordsEloquent = $eloquentModel->history_records;
        if (is_array($historyRecordsEloquent)) {
            $this->historyRecords = collect($historyRecordsEloquent)->map(function ($item) {
                return new HistoriesRecordSchema(
                    $item['title'],
                    $item['description'],
                    $item['start_date_yyyymm_str'],
                    $item['end_date_yyyymm_str'],
                );
            })->all();
        } else {
            $this->historyRecords = [];
        }
    }
}

<?php

namespace App\Models\Database\History;

class HistoryCategorySettingsSchema
{
    public string $id;
    public string $userAccountId;
    public array $categorySettings;

    public function __construct(
        $eloquentModel,
    ) {
        $this->id = $eloquentModel->_id;
        $this->userAccountId = $eloquentModel->user_account_id;
        $categorySettingsEloquent = $eloquentModel->category_settings;
        if (is_array($categorySettingsEloquent)) {
            $this->categorySettings = collect($categorySettingsEloquent)->map(function ($item) {
                return new HistoryCategorySettingsCategoriesSchema(
                    $item['category_id'],
                    $item['open_flag'],
                );
            })->all();
        } else {
            $this->categorySettings = [];
        }
    }
}

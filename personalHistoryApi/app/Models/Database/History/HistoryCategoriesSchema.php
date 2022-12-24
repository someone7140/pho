<?php

namespace App\Models\Database\History;

class HistoryCategoriesSchema
{
    public string $id;
    public string $name;
    public string $userAccountId;
    public bool $defaultSettingFlag;
    public int $defaultSort;

    public function __construct(
        $eloquentModel,
    ) {
        $this->id = $eloquentModel->_id;
        $this->name = $eloquentModel->name;
        $this->userAccountId = $eloquentModel->user_account_id;
        $this->defaultSettingFlag = $eloquentModel->default_setting_flag;
        $this->defaultSort = $eloquentModel->default_sort;
    }
}

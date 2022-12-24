<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Database\History\HistoryCategoriesEloquentModel;
use App\Models\Database\UserAccounts\UserAccountsEloquentModel;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 管理者ユーザ
        UserAccountsEloquentModel::create([
            '_id' => env('ADMIN_USER_ACCOUNT_ID'),
            'user_id' => "admin_user",
            'name' => "管理者",
            'is_account_open' => false,
            'occupation' => null,
            'description' => null,
            'icon_image_url' => null,
            'is_admin' => true,
            'auth_info' => [
                'email' => env('ADMIN_USER_EMAIL'),
                'password' => password_hash(env('ADMIN_USER_PASSWORD'), PASSWORD_DEFAULT),
                'gmail' => null,
                'twitter_id' => null,
            ],
            'external_info' => [
                'twitter_user_name' => null,
                'instagram_id' => null,
                'github_id' => null,
            ],
        ]);

        // ユーザ共通の経歴カテゴリー登録
        $historyCategoryEducation = new HistoryCategoriesEloquentModel();
        $historyCategoryEducation->_id = "ALL_USER_EDUCATION";
        $historyCategoryEducation->name = "学歴";
        $historyCategoryEducation->user_account_id = "ALL_USER";
        $historyCategoryEducation->default_setting_flag = true;
        $historyCategoryEducation->default_sort = 10;
        $historyCategoryEducation->save();

        $historyCategoryWork = new HistoryCategoriesEloquentModel();
        $historyCategoryWork->_id = "ALL_USER_WORK";
        $historyCategoryWork->name = "職歴";
        $historyCategoryWork->user_account_id = "ALL_USER";
        $historyCategoryWork->default_setting_flag = true;
        $historyCategoryWork->default_sort = 20;
        $historyCategoryWork->save();

        $historyCategoryLicense = new HistoryCategoriesEloquentModel();
        $historyCategoryLicense->_id = "ALL_USER_LICENSE";
        $historyCategoryLicense->name = "資格";
        $historyCategoryLicense->user_account_id = "ALL_USER";
        $historyCategoryLicense->default_setting_flag = true;
        $historyCategoryLicense->default_sort = 30;
        $historyCategoryLicense->save();

        $historyCategoryHobby = new HistoryCategoriesEloquentModel();
        $historyCategoryHobby->_id = "ALL_USER_HOBBY";
        $historyCategoryHobby->name = "趣味歴";
        $historyCategoryHobby->user_account_id = "ALL_USER";
        $historyCategoryHobby->default_setting_flag = true;
        $historyCategoryHobby->default_sort = 40;
        $historyCategoryHobby->save();
    }
}

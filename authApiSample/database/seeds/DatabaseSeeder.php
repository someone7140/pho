<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 管理者ユーザ登録
        $user = new User;
        $user->name = "管理者";
        $user->email = "admin@auth_sample.com";
        $user->password = Hash::make("admin_user_auth_sample");
        $user->role = config('const_user.ROLE_ADMIN');
        $user->status = config('const_user.STATUS_ACTIVE');
        $user->email_verified_at = new \DateTime();
        $user->save();
    }
}

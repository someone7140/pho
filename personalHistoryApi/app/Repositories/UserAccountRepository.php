<?php

namespace App\Repositories;

use \ErrorException;
use \Exception;
use App\Models\Database\UserAccounts\UserAccountsEloquentModel;

class UserAccountRepository
{
    public function createUser(
        $id,
        $userId,
        $name,
        $isAccountOpen,
        $occupation,
        $description,
        $iconImageUrl,
        $email,
        $password,
        $gmail,
        $twitterId,
        $twitterUserName,
        $instagramId,
        $gitHubId,
    ) {
        try {
            UserAccountsEloquentModel::create([
                '_id' => $id,
                'user_id' => $userId,
                'name' => $name,
                'is_account_open' => $isAccountOpen,
                'is_admin' => false,
                'occupation' => $occupation,
                'description' => $description,
                'icon_image_url' => $iconImageUrl,
                'auth_info' => [
                    'email' => $email,
                    'password' => $password,
                    'gmail' => $gmail,
                    'twitter_id' => $twitterId,
                ],
                'external_info' => [
                    'twitter_user_name' => $twitterUserName,
                    'instagram_id' => $instagramId,
                    'github_id' => $gitHubId,
                ],
            ]);
        } catch (Exception $ex) {
            throw new ErrorException('Can not create user');
        }
    }

    public function editUser(
        $id,
        $userId,
        $name,
        $isAccountOpen,
        $occupation,
        $description,
        $iconImageUrl,
        $twitterUserName,
        $instagramId,
        $gitHubId,
    ) {
        try {
            UserAccountsEloquentModel::where('_id', $id)->update([
                'user_id' => $userId,
                'name' => $name,
                'is_account_open' => $isAccountOpen,
                'occupation' => $occupation,
                'description' => $description,
                'icon_image_url' => $iconImageUrl,
                'external_info' => [
                    'twitter_user_name' => $twitterUserName,
                    'instagram_id' => $instagramId,
                    'github_id' => $gitHubId,
                ],
            ]);
        } catch (Exception $ex) {
            throw new ErrorException('Can not edit user');
        }
    }

    public function changePassword($id, $password)
    {
        try {
            UserAccountsEloquentModel::where('_id', $id)->update([
                'auth_info.password' =>  $password,
            ]);
        } catch (Exception $ex) {
            throw new ErrorException('Can not change password');
        }
    }

    public function getUserById($id)
    {
        return UserAccountsEloquentModel::where('_id', $id)->first();
    }

    public function getUserByUserId($userId)
    {
        return UserAccountsEloquentModel::where('user_id', $userId)->first();
    }

    public function getUserByGmail($gmail)
    {
        return UserAccountsEloquentModel::where('auth_info.gmail', $gmail)->first();
    }

    public function getUserByTwitterId($twitterId)
    {
        return UserAccountsEloquentModel::where('auth_info.twitter_id', $twitterId)->first();
    }

    public function getUserByEmail($email)
    {
        return UserAccountsEloquentModel::where('auth_info.email', $email)->first();
    }

    public function getOpenUsers()
    {
        return UserAccountsEloquentModel::where('is_account_open', true)
            ->orderBy('created_at', 'desc')
            ->take(50)->get();
    }
}

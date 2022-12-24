<?php

namespace App\Services\UserAccount;

use App\Models\Database\UserAccounts\UserAccountsSchema;
use App\Repositories\UserAccountRepository;

class UserAccountAuthService
{
    private $userAccountRepository;

    public function __construct(
        UserAccountRepository $userAccountRepository,
    ) {
        $this->userAccountRepository = $userAccountRepository;
    }

    public function getUserById($id)
    {
        $userEloquentModel = $this->userAccountRepository->getUserById($id);
        if (!isset($userEloquentModel)) {
            return null;
        }
        return new UserAccountsSchema($userEloquentModel);
    }

    public function getUserByUserId($userId)
    {
        $userEloquentModel = $this->userAccountRepository->getUserByUserId($userId);
        if (!isset($userEloquentModel)) {
            return null;
        }
        return new UserAccountsSchema($userEloquentModel);
    }

    public function getUserByGmail($gmail)
    {
        $userEloquentModel = $this->userAccountRepository->getUserByGmail($gmail);
        if (!isset($userEloquentModel)) {
            return null;
        }
        return new UserAccountsSchema($userEloquentModel);
    }

    public function getUserByTwitterId($twitterId)
    {
        $userEloquentModel = $this->userAccountRepository->getUserByTwitterId($twitterId);
        if (!isset($userEloquentModel)) {
            return null;
        }
        return new UserAccountsSchema($userEloquentModel);
    }

    public function getUserByEmail($email)
    {
        $userEloquentModel = $this->userAccountRepository->getUserByEmail($email);
        if (!isset($userEloquentModel)) {
            return null;
        }
        return new UserAccountsSchema($userEloquentModel);
    }

    public function changePassword($id, $password)
    {
        // DBに登録
        $this->userAccountRepository->changePassword($id, password_hash($password, PASSWORD_DEFAULT));
    }
}

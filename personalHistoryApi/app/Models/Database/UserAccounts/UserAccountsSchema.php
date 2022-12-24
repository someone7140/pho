<?php

namespace App\Models\Database\UserAccounts;

use \DateTime;
use \ErrorException;

class UserAccountsSchema
{
    public string $id;
    public string $userId;
    public string $name;
    public bool $isAccountOpen;
    public bool $isAdmin;
    public ?string $occupation;
    public ?string $description;
    public ?string $iconImageUrl;
    public ?UserAccountsAuthInfoSchema $authInfo;
    public ?UserAccountsExternalInfoSchema $externalInfo;
    public DateTime $createdAt;
    public DateTime $updatedAt;

    public function __construct(
        $eloquentModel,
    ) {
        $this->id = $eloquentModel->_id;
        $this->userId = $eloquentModel->user_id;
        $this->name = $eloquentModel->name;
        $this->isAccountOpen = $eloquentModel->is_account_open;
        $this->isAdmin = $eloquentModel->is_admin;
        $this->occupation = $eloquentModel->occupation;
        $this->description = $eloquentModel->description;
        $this->iconImageUrl = $eloquentModel->icon_image_url;

        if (isset($eloquentModel->auth_info)) {
            $this->authInfo = new UserAccountsAuthInfoSchema(
                $eloquentModel->auth_info["email"],
                $eloquentModel->auth_info["password"],
                $eloquentModel->auth_info["gmail"],
                $eloquentModel->auth_info["twitter_id"],
            );
        }
        if (isset($eloquentModel->external_info)) {
            $this->externalInfo = new UserAccountsExternalInfoSchema(
                $eloquentModel->external_info["twitter_user_name"],
                $eloquentModel->external_info["instagram_id"],
                $eloquentModel->external_info["github_id"],
            );
        }
        $this->createdAt = $eloquentModel->created_at;
        $this->updatedAt = $eloquentModel->updated_at;
    }

    public function getAuthMethod()
    {
        if (!isset($this->authInfo)) {
            throw new ErrorException('Can not get auth method');
        }
        if (isset($this->authInfo->email)) {
            return "email";
        } else if (isset($this->authInfo->gmail)) {
            return "gmail";
        } else if (isset($this->authInfo->twitterId)) {
            return "twitter";
        } else {
            throw new ErrorException('Can not get auth method');
        }
    }

    public function comparePasswordEmailAuth($password)
    {
        if (!isset($this->authInfo)) {
            return false;
        }
        return password_verify($password, $this->authInfo->password);
    }
}

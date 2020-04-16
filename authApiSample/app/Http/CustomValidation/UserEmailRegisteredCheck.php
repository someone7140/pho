<?php

namespace App\Http\CustomValidation;

use Illuminate\Contracts\Validation\Rule;

use App\User;
use App\Util\UserUtil;

class UserEmailRegisteredCheck implements Rule
{
    public function passes($attribute, $value)
    {
        $user = UserUtil::getUserFromEmail($value);
        if (isset($user)) {
            // メール認証中の時のみOKとする。
            return $user->status == config('const_user.STATUS_CONFIRMING');
        } else {
            // userがいなければOK
            return true;
        }
    }

    public function message()
    {
        return config('const_message.INPUT_ERROR_EMAIL_REGISTERED');
    }

}

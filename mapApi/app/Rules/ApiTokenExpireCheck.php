<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ApiTokenExpireCheck implements Rule
{

    const EXPIRE_DAY = 1;

    public function __construct()
    {
    }

    public function passes($attribute, $value)
    {
        $user = Auth::user();
        $register_date = $user->updated_at;
        $now = new \DateTime();
        $diff = $now->diff($register_date);
        return $diff->days < EXPIRE_DAY;
    }

    public function message()
    {
        return config('const_message.ERROR_API_TOKEN_EXPIRE');
    }
}

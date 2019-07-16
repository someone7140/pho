<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\User;

class EmailDuplicateCheck implements Rule
{
    public function __construct()
    {
    }

    public function passes($attribute, $value)
    {
        return count(User::where('email', $value)->get()) == 0;
    }

    public function message()
    {
        return config('const_message.ERROR_EMAIL_DUPLICATED');
    }
}

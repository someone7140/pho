<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRegistRequest;
use App\Services\UserService;

class AdminStoreUserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function regsitStore(UserRegistRequest $request)
    {
        return $this->userService->registUser(
            $request->name,
            $request->email,
            $request->password,
            config('const_user.ROLE_STORE')
        );
    }
}

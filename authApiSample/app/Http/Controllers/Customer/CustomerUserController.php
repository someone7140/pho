<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserEditRequest;
use App\Http\Requests\User\UserRegistRequest;
use App\Http\Requests\User\UserVerificationRequest;
use App\Services\UserService;

class CustomerUserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function regsitUser(UserRegistRequest $request)
    {
        return $this->userService->registUser(
            $request->name,
            $request->email,
            $request->password,
            config('const_user.ROLE_CUSTOMER')
        );
    }

    public function verificationUser(UserVerificationRequest $request)
    {
        return $this->userService->verificationUser(
            $request->user_id,
            $request->password,
            $request->token
        );
    }

    public function editUser(UserEditRequest $request)
    {
        return $this->userService->editUser(
            $request->name,
            $request->password
        );
    }
}

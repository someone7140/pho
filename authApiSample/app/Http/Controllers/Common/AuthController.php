<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        return $this->authService->login(
            $request->email,
            $request->password,
            $request->role
        );
    }

    public function logout()
    {
        return $this->authService->logout();
    }
}

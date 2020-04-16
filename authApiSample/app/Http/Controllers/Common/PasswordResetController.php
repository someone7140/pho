<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordResetRequest;
use App\Http\Requests\User\PasswordUpdateRequest;
use App\Services\PasswordResetService;

class PasswordResetController extends Controller
{
    protected $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    public function sendEmail(PasswordResetRequest $request)
    {
        return $this->passwordResetService->sendEmail($request->email);
    }
    public function updatePassword(PasswordUpdateRequest $request)
    {
        return $this->passwordResetService->updatePassword(
            $request->user_id, $request->password, $request->token
        );
    }
}

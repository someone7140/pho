<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

use App\Http\Response\Common\CommonResponse;
use App\User;
use App\Util\UserUtil;

class AuthService {
    public function login($email, $password, $role) {
        // エラー時のレスポンス定義
        $errorResponse = new CommonResponse();
        $errorResponse->status = config('const_http_status.UNAUTHORIZED_401');
        $errorResponse->errors = new \stdClass();
        try {
            $user = UserUtil::getUserFromEmail($email);
            if (isset($user)) {
                if (Auth::attempt(['email' => $email, 'password' => $password])) {
                    if ($user->status == config('const_user.STATUS_ACTIVE') && $user->role == $role) {
                        return UserUtil::getAuthenticatedResponse($user);
                    } else {
                        $errorResponse->errors->email = config('const_message.UNAUTHORIZED_ERROR_USER');
                        return $errorResponse->returnResponse();
                    }
                } else {
                    $errorResponse->errors->password = config('const_message.UNAUTHORIZED_ERROR_PASSWORD');
                    return $errorResponse->returnResponse();
                }
            } else {
                $errorResponse->errors->email = config('const_message.UNAUTHORIZED_ERROR_EMAIL');
                return $errorResponse->returnResponse();
            }
        } catch(Exception $ex) {
            $response = new CommonResponse();
            return $response->returnErrorWithMessage(
                config('const_http_status.INTERNAL_SERVER_ERROR_500'),
                $ex->getMessage()
            );
        }
    }

    public function logout() {
        try {
            $user = Auth::user();
            if(isset($user)) {
                // 既存のアクセストークンを削除
                UserUtil::deleteAccessToken($user->id);
            }
            $response = new CommonResponse();
            return $response->returnStatusOnly(config('const_http_status.OK_200'));
        } catch(Exception $ex) {
            $response = new CommonResponse();
            return $response->returnErrorWithMessage(
                config('const_http_status.INTERNAL_SERVER_ERROR_500'),
                $ex->getMessage()
            );
        }
    }

}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

use App\Http\Response\Common\CommonResponse;
use App\Models\User\PasswordReset;
use App\User;
use App\Util\MailUtil;
use App\Util\UserUtil;

class PasswordResetService {
    public function sendEmail($email) {
        $response = new CommonResponse();
        try {
            $user = UserUtil::getUserFromEmail($email);
            if (isset($user) && $user->status == config('const_user.STATUS_ACTIVE')) {
                $token = str_random(64);
                $success = MailUtil::sendEmailForPasswordReset($user->id, $user->email, $token);
                if ($success) {
                    $registeredPasswordReset = UserUtil::getPasseordResetFromUserId($user->id);
                    if (isset($registeredPasswordReset)) {
                        $registeredPasswordReset->delete();
                    }
                    // パスワードリセット登録
                    $passwordReset = new PasswordReset();
                    $passwordReset->user_id = $user->id;
                    $passwordReset->email = $user->email;
                    $passwordReset->token = $token;
                    $date = new \DateTime();
                    $date->modify('+1 days');
                    $passwordReset->expires_at = $date;
                    $passwordReset->save();
                    return $response->returnStatusOnly(config('const_http_status.OK_200'));
                } else {
                    return $response->returnErrorWithMessage(
                        config('const_http_status.BAD_REQUEST_400'),
                        config('const_message.SEND_ERROR_EMAIL')
                    ); 
                }
            } else {
                return $response->returnErrorWithMessage(
                    config('const_http_status.BAD_REQUEST_400'),
                    config('const_message.UNAUTHORIZED_ERROR_USER')
                );
            }
        } catch(Exception $ex) {
            return $response->returnErrorWithMessage(
                config('const_http_status.INTERNAL_SERVER_ERROR_500'),
                $ex->getMessage()
            );
        }
    }

    public function updatePassword($userId, $password, $token) {
        // エラー時のレスポンス定義
        $errorResponse = new CommonResponse();
        $errorResponse->status = config('const_http_status.UNAUTHORIZED_401');
        $errorResponse->errors = new \stdClass();
        try {
            $user = UserUtil::getUserFromUserId($userId);
            if (isset($user) && $user->status == config('const_user.STATUS_ACTIVE')) {
                $passwordReset = UserUtil::getPasseordResetFromUserId($user->id);
                if(isset($passwordReset)) {
                    $now = new \DateTime();
                    $expiresAt = new \DateTime($passwordReset->expires_at);
                    if ($passwordReset->token == $token && $now <= $expiresAt) {
                        // パスワードの更新
                        UserUtil::editUser($user, null, $password);
                        // passwordResetの削除
                        $passwordReset->delete();
                        $response = new CommonResponse();
                        return $response->returnStatusOnly(config('const_http_status.OK_200'));
                    }
                } else {
                    $errorResponse->errors->token = config('const_message.PASSWORD_RESET_ERROR_TOKEN');
                    return $errorResponse->returnResponse();
                }
            } else {
                $errorResponse->errors->user_id = config('const_message.UNAUTHORIZED_ERROR_USER');
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

}

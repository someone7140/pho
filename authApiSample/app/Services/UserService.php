<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Http\Response\Common\CommonResponse;
use App\Models\User\EmailVerification;
use App\User;
use App\Util\MailUtil;
use App\Util\UserUtil;

class UserService {
    public function registUser($name, $email, $password, $role) {
        $response = new CommonResponse();
        $emailVerifiedAt = null;
        $status = null;
        try {
            // Customer特有の処理
            if ($role == config('const_user.ROLE_CUSTOMER')) {
                $status = config('const_user.STATUS_CONFIRMING');
                $registerdUser = UserUtil::getUserFromEmail($email);
                if (isset($registerdUser) && $registerdUser->status == config('const_user.STATUS_CONFIRMING')) {
                    // email認証テーブルのレコードがある場合は削除
                    $emailVerification = UserUtil::getEmailVerificationFromUserId($registerdUser->id);
                    if(isset($emailVerification)) {
                        $emailVerification->delete();
                    }
                    // 既存の確認中レコードがある場合は削除
                    $registerdUser->delete();
                }
            }
            // Store特有の処理
            if ($role == config('const_user.ROLE_STORE')) {
                $status = config('const_user.STATUS_ACTIVE');
                $emailVerifiedAt = new \DateTime();
            }
            // ユーザ登録
            $user = new User;
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->role = $role;
            $user->status = $status;
            $user->email_verified_at = $emailVerifiedAt;
            $user->save();

            // Customerのメール送信処理
            if ($role == config('const_user.ROLE_CUSTOMER')) {
                $token = str_random(64);
                $success = MailUtil::sendEmailForVerification($user->id, $user->email, $token);
                if ($success) {
                    // email認証登録
                    $emailVerification = new EmailVerification();
                    $emailVerification->user_id = $user->id;
                    $emailVerification->email = $user->email;
                    $emailVerification->token = $token;
                    $date = new \DateTime();
                    $date->modify('+1 days');
                    $emailVerification->expires_at = $date;
                    $emailVerification->save();
                } else {
                    return $response->returnErrorWithMessage(
                        config('const_http_status.BAD_REQUEST_400'),
                        config('const_message.SEND_ERROR_EMAIL')
                    ); 
                }
            }
            // <TODO>Storeのテーブルレコード作成
            return $response->returnStatusOnly(config('const_http_status.OK_200'));
        } catch(Exception $ex) {
            $response = new CommonResponse();
            return $response->returnErrorWithMessage(
                config('const_http_status.INTERNAL_SERVER_ERROR_500'),
                $ex->getMessage()
            );
        }
    }

    public function verificationUser($userId, $password, $token) {
        // エラー時のレスポンス定義
        $errorResponse = new CommonResponse();
        $errorResponse->status = config('const_http_status.UNAUTHORIZED_401');
        $errorResponse->errors = new \stdClass();
        try {
            $user = UserUtil::getUserFromUserId($userId);
            if (isset($user) && $user->status == config('const_user.STATUS_CONFIRMING')) {
                if (Auth::attempt(['email' => $user->email, 'password' => $password])) {
                    $emailVerification = UserUtil::getEmailVerificationFromUserId($userId);
                    if (isset($emailVerification)) {
                        $now = new \DateTime();
                        $expiresAt = new \DateTime($emailVerification->expires_at);
                        if ($emailVerification->token == $token && $now <= $expiresAt)  {
                            // userのステータス更新
                            $user->status = config('const_user.STATUS_ACTIVE');
                            $user->email_verified_at = $now;
                            $user->save();
                            // emailVerificationの削除
                            $emailVerification->delete();
                            return UserUtil::getAuthenticatedResponse($user);
                        } else {
                            $errorResponse->errors->token = config('const_message.VERIFICATION_ERROR_TOKEN');
                            return $errorResponse->returnResponse();
                        }
                    } else {
                        $errorResponse->errors->token = config('const_message.VERIFICATION_ERROR_TOKEN');
                        return $errorResponse->returnResponse();
                    }
                } else {
                    $errorResponse->errors->password = config('const_message.UNAUTHORIZED_ERROR_PASSWORD');
                    return $errorResponse->returnResponse();
                }
            } else {
                $errorResponse->errors->user_id = config('const_message.VERIFICATION_ERROR_USER');
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

    public function editUser($name, $password) {
        try {
            $user = Auth::user();
            if(isset($user)) {
                UserUtil::editUser($user, $name, $password);
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

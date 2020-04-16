<?php

namespace App\Util;

use Illuminate\Support\Facades\Hash;

use App\Http\Response\User\AuthenticatedResponse;
use App\Models\User\EmailVerification;
use App\Models\User\OauthAccessToken;
use App\Models\User\PasswordReset;
use App\User;

class UserUtil {
    public static function getUserFromEmail($email) {
        return User::where('email', $email)->first();
    }

    public static function getUserFromUserId($userId) {
        return User::where('id', $userId)->first();
    }

    public static function deleteAccessToken($userId) {
        $tokens = OauthAccessToken::where('user_id', $userId);
        $tokens->delete();
    }

    public static function getAuthenticatedResponse($user) {
        // 既存のアクセストークンを削除
        self::deleteAccessToken($user->id);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = new AuthenticatedResponse();
        return $response->authenticatedResponse($user->id, $user->name, $user->role, $token);
    }
 
    public static function getEmailVerificationFromUserId($userId) {
        return EmailVerification::where('user_id', $userId)->first();
    }

    public static function getPasseordResetFromUserId($userId) {
        return PasswordReset::where('user_id', $userId)->first();
    }
 
    public static function editUser($user, $name, $password) {
        if (isset($name)) {
            $user->name = $name;
        }
        if (isset($password)) {
            $user->password = Hash::make($password);
        }
        $user->save();
    }
}

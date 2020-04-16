<?php

namespace App\Util;

use Mail;

use App\Mail\EmailVerificationNotification;
use App\Mail\PasswordResetNotification;

class MailUtil {
    public static function sendEmailForVerification($userId, $email, $token) {
        $velificationLink = env('VIEW_URL', 'http://localhost') .
            "/email_verification?user_id=" . $userId .
            "&token=" . $token;
        try {
            Mail::to($email)->send( new EmailVerificationNotification($velificationLink) );
            return true;
        } catch (Exception $ex) {
            return false;
        }

    }

    public static function sendEmailForPasswordReset($userId, $email, $token) {
        $velificationLink = env('VIEW_URL', 'http://localhost') .
            "/password_reset_regist?user_id=" . $userId .
            "&token=" . $token;
        try {
            Mail::to($email)->send( new PasswordResetNotification($velificationLink) );
            return true;
        } catch (Exception $ex) {
            return false;
        }

    }
}
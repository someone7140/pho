<?php

namespace App\Services\Auth;

use \ErrorException;
use \Exception;
use Mailgun\Mailgun;


use App\Models\Database\Auth\EmailAuthTempsSchema;
use App\Repositories\EmailAuthTempRepository;

class EmailAuthService
{

    private $emailAuthTempRepository;
    public function __construct(
        EmailAuthTempRepository $emailAuthTempRepository,
    ) {
        $this->emailAuthTempRepository = $emailAuthTempRepository;
    }

    // 認証用メール送信
    public function authEmailSend($email, $password)
    {
        // 登録用ID
        $uid = uniqid(mt_rand(), true);
        // MailGunでメール送信
        $this->sendEmail($uid, $email);
        // メール認証情報をDB登録
        $this->registerEmailAuthTemp($uid, $email, $password);
    }

    // 認証情報のチェック
    public function authRegisteredEmail($id, $password)
    {
        // 認証データの取得
        $emailAuthTemp = $this->getEmailAuthTempById($id);
        if (!isset($emailAuthTemp)) {
            return false;
        }
        // 認証データのチェック
        if ($emailAuthTemp->isExpired() || !$emailAuthTemp->comparePassword($password)) {
            return false;
        }
        return true;
    }

    // 認証情報のIDでDBから情報取得
    public function getEmailAuthTempById($id)
    {
        $emailAuthTemp = $this->emailAuthTempRepository->getEmailAuthTempById($id);
        if (!isset($emailAuthTemp)) {
            return null;
        }
        return new EmailAuthTempsSchema($emailAuthTemp);
    }

    // MailGunでメール送信
    private function sendEmail($registerId, $email)
    {
        $mg = Mailgun::create(env('MAILGUN_API_KEY'));
        $authUrl = env('FRONT_END_URL') . "/auth/email_auth?register_id=" . $registerId;
        $body = '以下のURLから会員登録をしてください。有効期限は1日です。' . "\n" . $authUrl;

        try {
            $mg->messages()->send(env('MAILGUN_DOMAIN'), [
                'from'    => 'no-reply@personal-history.com',
                'to'      => $email,
                'subject' => '【私の履歴書】会員登録',
                'text'    => $body
            ]);
        } catch (Exception $e) {
            throw new ErrorException('Can not send mail');
        }
    }

    // メール認証情報をDB登録
    private function registerEmailAuthTemp($id, $email, $password)
    {
        // 該当emailデータの削除
        $this->emailAuthTempRepository->deleteEmailAuthTempByEmail($email);
        // データの登録
        $this->emailAuthTempRepository->createEmailAuthTemp(
            $id,
            $email,
            password_hash($password, PASSWORD_DEFAULT)
        );
    }
}

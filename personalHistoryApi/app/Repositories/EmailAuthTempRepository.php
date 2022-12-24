<?php

namespace App\Repositories;

use \ErrorException;
use \Exception;
use App\Models\Database\Auth\EmailAuthTempsEloquentModel;

class EmailAuthTempRepository
{
    public function createEmailAuthTemp(
        $id,
        $email,
        $password,
    ) {
        try {
            EmailAuthTempsEloquentModel::create([
                '_id' => $id,
                'email' => $email,
                'password' => $password,
            ]);
        } catch (Exception $ex) {
            throw new ErrorException('Can not create email auth temp');
        }
    }

    public function deleteEmailAuthTempByEmail(
        $email,
    ) {
        EmailAuthTempsEloquentModel::where('email', $email)->delete();
    }

    public function getEmailAuthTempById($id)
    {
        return EmailAuthTempsEloquentModel::where('_id', $id)->first();
    }
}

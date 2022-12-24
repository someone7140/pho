<?php

namespace App\Models\Database\Auth;

use \DateTime;
use \DateTimeImmutable;
use \DateTimeZone;

class EmailAuthTempsSchema
{
    public string $id;
    public string $email;
    public string $password;
    public DateTime $updatedAt;

    public function __construct(
        $eloquentModel,
    ) {
        $this->id = $eloquentModel->_id;
        $this->email = $eloquentModel->email;
        $this->password = $eloquentModel->password;
        $this->updatedAt = $eloquentModel->updated_at;
    }

    public function isExpired()
    {
        $updatedAtPlusOneDate = DateTimeImmutable::createFromMutable($this->updatedAt)->modify('+1 day');
        $nowUtc = new DateTimeImmutable('now', new DateTimeZone('GMT'));
        return $updatedAtPlusOneDate < $nowUtc;
    }


    public function comparePassword($password)
    {
        return password_verify($password, $this->password);
    }
}

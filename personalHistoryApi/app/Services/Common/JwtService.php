<?php

namespace App\Services\Common;

use \ErrorException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{

    public function getEncodedToken($inputPayloadDict, $limitTime)
    {
        // 期限を追加
        $payloadDict = $inputPayloadDict + array('expire' => time() + $limitTime);
        return JWT::encode($payloadDict, env('JWT_SECRET_KEY'), env('JWT_ALGORITHMS'));
    }

    public function getDecodedResult($token)
    {
        $decodedResult =  JWT::decode($token, new Key(env('JWT_SECRET_KEY'), env('JWT_ALGORITHMS')));
        if ($decodedResult->expire < time()) {
            throw new ErrorException('Expired token limit');
        }
        return $decodedResult;
    }
}

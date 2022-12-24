<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Services\UserAccount\UserAccountService;

class AuthenticateToken
{
    private $userAccountService;
    public function __construct(
        UserAccountService $userAccountService,
    ) {
        $this->userAccountService = $userAccountService;
    }

    public function handle(Request $request, Closure $next)
    {

        $jwtToken = $request->bearerToken();

        if (isset($jwtToken)) {
            // jwtTokenを複合化&userのidを取得
            $id = $this->userAccountService->decodeUserIdToken($jwtToken);
            $request->merge(['userAccountId' => $id]);
            return $next($request);
        }
        // トークン内容が取得できなかったらエラーを返す
        return response()->json(
            [
                'message' =>  'Can not authorize token'
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }
}

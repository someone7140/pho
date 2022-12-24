<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Services\UserAccount\UserAccountService;

class AuthenticateAdmin
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

        if (!isset($jwtToken)) {
            // トークン内容が取得できなかったらエラーを返す
            return response()->json(
                [
                    'message' =>  'Can not authorize token'
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        // jwtTokenを複合化&userのidを取得
        $id = $this->userAccountService->decodeUserIdToken($jwtToken);
        // adminのIDであるか
        if ($id == env('ADMIN_USER_ACCOUNT_ID')) {
            $request->merge(['userAccountId' => $id]);
            return $next($request);
        }

        // adminでなかったらエラーを返す
        return response()->json(
            [
                'message' =>  'Not admin user'
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\Http\Response\Common\CommonResponse;

class CustomerAuthCheck
{

    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if(isset($user) && $user->role == config('const_user.ROLE_CUSTOMER')) {
            return $next($request);
        } else {
            $response = new CommonResponse();
            return response()->json(
                $response->returnErrorWithMessageNonJsonEncode(
                    config('const_http_status.UNAUTHORIZED_401'),
                    config('const_message.UNAUTHORIZED_ERROR_NOTAUTH')
                ),
                config('const_http_status.OK_200')
            );
        }
    }
}

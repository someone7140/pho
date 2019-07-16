<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Db\Increment;
use App\Models\Response\CommonResponse;
use App\Models\Response\UserResponse;

class UserController extends Controller
{
    use SendsPasswordResetEmails;
    public function create(CreateUserRequest $request)
    {
        // incrementでuser_idのキーを取得
        $increment = Increment::where('key', 'user_id')->first();
        $user = new User;
        $user->_id = $increment->get_user_id();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->status = config('const_user_status.active');
        $user->save();
        $response = new CommonResponse();
        $response->status = config('const_http_status.OK_200');
        return $response->return_response();
    }
    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if($user->status == config('const_user_status.active')) {
                // 認証に成功
                $response = new UserResponse();
                $response->status = config('const_http_status.OK_200');
                $response->name = $user->name;
                return $response->return_response();
            } else {
                // 認証に失敗
                $response = new CommonResponse();
                $response->status = config('const_http_status.BAD_REQUEST_400');
                $response->message = new \stdClass();
                $response->message->email = [config('const_message.ERROR_ACCOUNT_LOCK')];
                return $response->return_response();
            }
        } else {
            $response = new CommonResponse();
            $response->status = config('const_http_status.BAD_REQUEST_400');
            $response->message = new \stdClass();
            $response->message->email = [config('const_message.ERROR_LOGIN')];
            return $response->return_response();
        }
    }
    public function user_info(Request $request)
    {
        $user = Auth::user();
        $response = new UserResponse();
        $response->status = config('const_http_status.OK_200');
        $response->name = $user->name;
        $response->email = $user->email;
        return $response->return_response();
    }
    public function password_reset(Request $request)
    {
        $this->validateEmail($request);
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        return "sss";
    }
}

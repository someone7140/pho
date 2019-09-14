<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\OnlyApiTokenRequest;
use App\Models\Db\Increment;
use App\Models\Response\CommonResponse;
use App\Models\Response\UserResponse;

class UserController extends Controller
{
    use SendsPasswordResetEmails;
    public function create(UserRequest $request)
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
    public function update(UserRequest $request)
    {
        $user = Auth::user();
        if ($request->file('image_file')->isValid([])) {
            $aaa = file_get_contents($request->file('image_file'));
        }
        $user->name = $request->name;
        $user->email = $request->email;
        if (isset($request->password) && mb_strlen($request->$password) > 0) {
            $user->email = $request->$password;
        }
        $user->save();
        $response = new CommonResponse();
        $response->status = config('const_http_status.OK_200');
        return $response->return_response();
    }
    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            // 認証に成功
            if($user->status == config('const_user_status.active')) {
                // tokenの生成
                $api_token = Str::random(60);
                $request->user()->forceFill([
                    'api_token' => hash('sha256', $api_token),
                ])->save();
                $response = new UserResponse();
                $response->status = config('const_http_status.OK_200');
                $response->name = $user->name;
                $response->api_token = $api_token;
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
    public function user_info(OnlyApiTokenRequest $request)
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

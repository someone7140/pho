<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// 未認証でアクセスできるAPI
Route::match (['post', 'options'], 'login',  'Common\AuthController@login')->name('login');
Route::match (['post', 'options'], 'password_reset_send_email',  'Common\PasswordResetController@sendEmail')->name('sendEmail');
Route::match (['post', 'options'], 'password_reset_update_password',  'Common\PasswordResetController@updatePassword')->name('updatePassword');
Route::match (['post', 'options'], 'customer/user_regist',  'Customer\CustomerUserController@regsitUser')->name('regsitUser');
Route::match (['post', 'options'], 'customer/user_verification',  'Customer\CustomerUserController@verificationUser')->name('verificationUser');

// 認証が必要なAPI
Route::group(['middleware' => ['auth:api']], function () {
    // ロール共通
    Route::match (['post', 'options'], 'logout',  'Common\AuthController@logout')->name('logout');
    // 利用者ロール
    Route::group(['middleware' => ['auth.customer.check']], function () {
        Route::match (['post', 'options'], 'customer/user_edit',  'Customer\CustomerUserController@editUser')->name('editUser');
    });
    // 管理者ロール
    Route::group(['middleware' => ['auth.admin.check']], function () {
        Route::match (['post', 'options'], 'admin/store_regist',  'Admin\AdminStoreUserController@regsitStore')->name('regsitStore');
    });
});

<?php

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

Route::match(['post', 'options'], 'userAccount/registerByGoogleAuthCode', 'App\Http\Controllers\UserAccountRegisterController@registerByGoogleAuthCode')->name('userAccount.registerByGoogleAuthCode');
Route::match(['post', 'options'], 'userAccount/registerByTwitterAuthCode', 'App\Http\Controllers\UserAccountRegisterController@registerByTwitterAuthCode')->name('userAccount.registerByTwitterAuthCode');
Route::match(['post', 'options'], 'userAccount/registerByEmail', 'App\Http\Controllers\UserAccountRegisterController@registerByEmail')->name('userAccount.registerByEmail');
Route::match(['post', 'options'], 'userAccount/registerUserAccount', 'App\Http\Controllers\UserAccountRegisterController@registerUserAccount')->name('userAccount.registerUserAccount');
Route::match(['post', 'options'], 'userAccount/authEmailRegistered', 'App\Http\Controllers\UserAccountRegisterController@authEmailRegistered')->name('userAccount.authEmailRegistered');
Route::match(['get', 'options'], 'userAccount/getOpenUserList', 'App\Http\Controllers\UserAccountRefController@getOpenUserList')->name('userAccount.getOpenUserList');

Route::match(['get', 'options'], 'history/getHistoryInfoByUserId', 'App\Http\Controllers\HistoryController@getHistoryInfoByUserId')->name('history.getHistoryInfoByUserId');

Route::match(['post', 'options'], 'login/loginByGoogleAuthCode', 'App\Http\Controllers\UserAccountAuthController@loginByGoogleAuthCode')->name('login.loginByGoogleAuthCode');
Route::match(['post', 'options'], 'login/loginByTwitterAuthCode', 'App\Http\Controllers\UserAccountAuthController@loginByTwitterAuthCode')->name('login.loginByTwitterAuthCode');
Route::match(['post', 'options'], 'login/loginByEmail', 'App\Http\Controllers\UserAccountAuthController@loginByEmail')->name('login.loginByEmail');

Route::match(['get'], 'announcement/getRecentAnnouncements', 'App\Http\Controllers\AnnouncementController@getRecentAnnouncements')->name('announcement.getRecentAnnouncements');
Route::match(['get'], 'announcement/getAnnouncementById', 'App\Http\Controllers\AnnouncementController@getAnnouncementById')->name('announcement.getAnnouncementById');

Route::group(['middleware' => ['authToken']], function () {
    Route::match(['post'], 'userAccount/changePassword', 'App\Http\Controllers\UserAccountAuthController@changePassword')->name('userAccount.changePassword');
    Route::match(['get'], 'userAccount/getMyUserInfo', 'App\Http\Controllers\UserAccountRefController@getMyUserInfo')->name('userAccount.getMyUserInfo');
    Route::match(['post'], 'userAccount/editUserAccount', 'App\Http\Controllers\UserAccountRegisterController@editUserAccount')->name('userAccount.editUserAccount');
    Route::match(['post'], 'historyCategory/registerCategory', 'App\Http\Controllers\HistoryCategoryController@registerCategory')->name('historyCategory.registerCategory');
    Route::match(['get'], 'historyCategory/getMyCategory', 'App\Http\Controllers\HistoryCategoryController@getMyCategory')->name('historyCategory.getMyCategory');
    Route::match(['post'], 'history/registerHistory', 'App\Http\Controllers\HistoryController@registerHistory')->name('history.registerHistory');
    Route::match(['get'], 'history/getOwnHistories', 'App\Http\Controllers\HistoryController@getOwnHistories')->name('history.getOwnHistories');
});

Route::group(['middleware' => ['authAdmin']], function () {
    Route::match(['post'], 'announcement/addAnnouncement', 'App\Http\Controllers\AnnouncementController@addAnnouncement')->name('announcement.addAnnouncement');
    Route::match(['post'], 'announcement/editAnnouncement', 'App\Http\Controllers\AnnouncementController@editAnnouncement')->name('announcement.editAnnouncement');
    Route::match(['post'], 'announcement/deleteAnnouncement', 'App\Http\Controllers\AnnouncementController@deleteAnnouncement')->name('announcement.deleteAnnouncement');
});

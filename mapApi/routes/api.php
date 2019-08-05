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
Route::match (['get', 'options'], 'map/script', 'MapController@script')->name('map.script'); 
Route::match (['get', 'options'], 'map/address', 'MapController@address')->name('map.address');

Route::match (['post', 'options'], 'user/create', 'UserController@create')->name('user.create');
Route::match (['post', 'options'], 'user/login', 'UserController@login')->name('user.login');
Route::match (['post', 'options'], 'user/password_reset', 'UserController@password_reset')->name('user.password_reset');

Route::match (['get', 'options'], 'event/list', 'EventController@list')->name('event.list');
// Route::match (['get', 'options'], 'event/findByUser', 'EventController@findByUser')->name('event.findByUser');

Route::group(['middleware' => ['auth:api']], function () {
    // 認証が必要なメソッド
    Route::match (['get', 'options'], 'user/user_info', 'UserController@user_info')->name('user.user_info');
    Route::match (['post', 'options'], 'user/update', 'UserController@update')->name('user.update');

    Route::match (['post', 'options'], 'event/create', 'EventController@create')->name('event.create');
});

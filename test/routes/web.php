<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','TopicsController@index')->name('topics.index');
//Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
//登录路由
Route::get('login','Auth\LoginController@showLoginForm')->name('login');
Route::post('login','Auth\LoginController@login');
Route::post('logout','Auth\LoginController@logout')->name('logout');

//用户注册相关路由
Route::get('register','Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register','Auth\RegisterController@register');

//密码重置相关的
Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset','Auth\ResetPasswordController@reset')->name('password.update');

//邮箱认证相关路由
Route::get('email/verify','Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}','Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend','Auth\VerificationController@resend')->name('verification.resend');

//个人主页
Route::resource('users','UsersController',['only'=>['show','update','edit']]);
Route::resource('notification','NotificationController',['only'=>['index']]);
//Route::get('users/{user}/{tab?}','UsersController@show')->name('users.show');
//话题
Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::get('topics/{topic}/{slug?}','TopicsController@show')->name('topics.show');
Route::post('upload_image','TopicsController@uploadImage')->name('topics.upload_image');
//话题分类
Route::resource('categories','CategoriesController',['only'=>['show']]);
//话题回复
Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);

//Wechat
//Route::prefix('wechat')->name('wechat')->group(['middleware' => ['web', 'wechat.oauth']],function () {
//    Route::get('oauth','WechatController@oauth')->name('oauth');
//});

Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
    Route::get('wechat/qrOauth/{key?}','WechatController@qrOauth')->name('oauth');
});





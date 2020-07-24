<?php

use Illuminate\Http\Request;


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



Route::prefix('v1')->namespace('Api')->name('api.v1.')->group(function() {

    Route::middleware('throttle:'.config('api.rate_limits.sign'))->group(function(){
        //获取图形验证码
        Route::post('captchas','CaptchasController@store')
            ->name('captchas.store');
        //短信验证码
        Route::post('verificationCodes','VerificationCodesController@store')
            ->name('verificationCodes.store');

        //登录二维码
        Route::get('loginQr','CaptchasController@loginQr')->name('captchas.loginQr');


    });

    Route::middleware('throttle:'.config('api.rate_limits.access'))->group(function(){
        //第三方登录
        Route::post('socials/{social_type}/authorizations','AuthorizationsController@socialStore')
            ->where('social_type','weixin')
            ->name('socials.authorizations.store');
        //用户名密码登录
        Route::post('authorizations','AuthorizationsController@store')
            ->name('api.authorizations.store');

        //手机号登录
        Route::post('phone/authorizations','AuthorizationsController@phoneStore')
            ->name('phone.authorizations.store');

        //二维码登录
        Route::post('qrcode/authorizations','AuthorizationsController@qrcodeStore')
            ->name('qrcode.authorizations.store');
        //小程序登录
        Route::post('weapp/authorizations','AuthorizationsController@weappStore')
            ->name('api.weapp.authorizations.store');

        Route::put('authorizations/current','AuthorizationsController@update')
            ->name('api.authorizations.update');

        Route::delete('authorizations/current','AuthorizationsController@destroy')
            ->name('api.authorizations.destroy');



    });


    Route::middleware('throttle:'.config('api.rate_limits.access'))->group(function(){
        //用户登录查看接口
        Route::middleware('auth:api')->group(function() {
            // 当前登录用户信息
            Route::get('user', 'UsersController@me')
                ->name('user.show');
            Route::patch('user/update','UsersController@update')
                ->name('user.update');

            Route::post('images','ImagesController@store')
                ->name('user.image.store');

            // 发布话题
            Route::resource('topics','TopicsController',['only'=>[
                'store','update','destroy'
            ]]);

            //重置密码
            Route::post('reset/password','AuthorizationsController@resetPassword')
                ->name('api.authorizations.reset.password');

            //作品
            Route::resource('works','WorksController',['only'=>[
                'store','update','destroy','edit'
            ]]);
            Route::post('works/{work}/release','WorksController@release')
                ->name('projects.release');

            //收藏-用户收藏作品
            Route::resource('favoriteWorks','FavoriteWorksController',['only'=>[
                'store','update','destroy','edit'
            ]]);
            //收藏-用户已收藏列表
            Route::get('favoriteWorks/user/index','FavoriteWorksController@myIndex')
                ->name('favoriteWorks.myIndex');
            //喜欢-喜欢作品
            Route::resource('likeWorks','LikeWorksController',['only'=>[
                'store','destroy'
            ]]);

            //用户消息列表
            Route::get('notifications','NotificationsController@index')
                ->name('notifications.index');

            //用户消息未读统计
            Route::get('notifications/stats','NotificationsController@stats')
                ->name('notifications.stats');

        });

        //用户未登录查看接口
        Route::get('users/{user}','UsersController@show')
            ->name('users.show');
        //分类
        Route::get('categories','CategoriesController@show')
            ->name('categories.show');
        //话题
        Route::resource('topics','TopicsController',['only'=>['index','show']]);
        //用户发布话题列表
        Route::get('users/{user}/topics','TopicsController@userIndex')->name('users.topics');
        //轮播图
        Route::get('index/banners','IndexController@banners')->name('banner');

        //作品
        Route::resource('works','WorksController',['only'=>['index','show']]);
        //用户已发布作品列表
        Route::get('users/{user}/works','WorksController@userIndex')
            ->name('users.works');

        //喜欢-用户已喜欢列表
        Route::get('likeWorks/user/index/{user}','LikeWorksController@userIndex')
            ->name('likeWorks.userIndex');
        //用户主页
        Route::get('profile/{user}','ProfileController@index')
            ->name('profile.index');

    });






});


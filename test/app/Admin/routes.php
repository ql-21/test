<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->resource('auth/users', 'AdminUserController');
    $router->resource('auth/roles', 'AdminRolesController');

    $router->get('/', 'HomeController@index')->name('admin.home');

    $router->resource('users', UsersController::class);

    $router->resource('topics', TopicsController::class);

    $router->resource('categories', CategoriesController::class);

    $router->resource('banners', BannersController::class);

    $router->resource('images', ImageController::class);


});

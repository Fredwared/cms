<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 13:59
 */

Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
    Route::get('/login', ['as' => 'backend.auth.login', 'uses' => 'IndexController@getLogin']);
    Route::post('/login', ['as' => 'backend.auth.login.post', 'uses' => 'IndexController@postLogin']);
    Route::get('/logout', ['as' => 'backend.auth.logout', 'middleware' => ['web'], 'uses' => 'IndexController@logout']);
    Route::match(['get', 'put'], '/forgot-password', ['as' => 'backend.auth.forgotpass', 'uses' => 'IndexController@forgotPass']);
    Route::match(['get', 'put'], '/reset-password/{key}', ['as' => 'backend.auth.resetpass', 'uses' => 'IndexController@resetPass']);
    Route::get('/message', ['as' => 'backend.auth.message', 'uses' => 'IndexController@message']);
});
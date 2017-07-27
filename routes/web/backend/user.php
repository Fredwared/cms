<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 14:05
 */

Route::group(['prefix' => 'user'], function () {
    Route::get('/', ['as' => 'backend.user.index', 'uses' => 'UserController@index']);
    Route::match(['get', 'put'], '/info', ['as' => 'backend.user.profile', 'uses' => 'UserController@userInfo']);
    Route::match(['get', 'put'], '/avatar', ['as' => 'backend.user.avatar', 'uses' => 'UserController@avatar']);
    Route::get('/create', ['as' => 'backend.user.create', 'uses' => 'UserController@create']);
    Route::post('/store', ['as' => 'backend.user.store', 'uses' => 'UserController@store']);
    Route::get('/{userInfo}', ['as' => 'backend.user.edit', 'uses' => 'UserController@edit']);
    Route::put('/{userInfo}', ['as' => 'backend.user.update', 'uses' => 'UserController@update']);
    Route::post('/forgot-passord/{userInfo}', ['as' => 'backend.user.forgotpass', 'uses' => 'UserController@forgotPass']);
    Route::delete('/destroy/{userInfo}', ['as' => 'backend.user.destroy', 'uses' => 'UserController@destroy']);
    Route::post('/change-status/{status}', ['as' => 'backend.user.changestatus', 'uses' => 'UserController@changeStatus']);
});
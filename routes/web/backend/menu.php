<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 14:05
 */

Route::group(['prefix' => 'menu'], function () {
    Route::get('/', ['as' => 'backend.menu.index', 'uses' => 'MenuController@index']);
    Route::get('/create', ['as' => 'backend.menu.create', 'uses' => 'MenuController@create']);
    Route::post('/store', ['as' => 'backend.menu.store', 'uses' => 'MenuController@store']);
    Route::match(['get', 'put'], '/sort', ['as' => 'backend.menu.sort', 'uses' => 'MenuController@sort']);
    Route::get('/{menuInfo}', ['as' => 'backend.menu.edit', 'uses' => 'MenuController@edit']);
    Route::put('/{menuInfo}', ['as' => 'backend.menu.update', 'uses' => 'MenuController@update']);
    Route::delete('/destroy/{$menuInfo}', ['as' => 'backend.menu.destroy', 'uses' => 'MenuController@destroy']);
    Route::post('/change-status/{status}', ['as' => 'backend.menu.changestatus', 'uses' => 'MenuController@changeStatus']);
});
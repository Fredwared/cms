<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 14:05
 */

Route::group(['prefix' => 'group'], function () {
    Route::get('/', ['as' => 'backend.group.index', 'uses' => 'GroupController@index']);
    Route::get('/create', ['as' => 'backend.group.create', 'uses' => 'GroupController@create']);
    Route::post('/store', ['as' => 'backend.group.store', 'uses' => 'GroupController@store']);
    Route::get('/{groupInfo}', ['as' => 'backend.group.edit', 'uses' => 'GroupController@edit']);
    Route::put('/{groupInfo}', ['as' => 'backend.group.update', 'uses' => 'GroupController@update']);
    Route::get('/profile/{groupInfo}', ['as' => 'backend.group.profile', 'uses' => 'GroupController@profile']);
    Route::delete('/destroy/{groupInfo}', ['as' => 'backend.group.destroy', 'uses' => 'GroupController@destroy']);
    Route::post('/change-status/{status}', ['as' => 'backend.group.changestatus', 'uses' => 'GroupController@changeStatus']);
});
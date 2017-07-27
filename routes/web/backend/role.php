<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 14:05
 */

Route::group(['prefix' => 'role'], function () {
    Route::get('/', ['as' => 'backend.role.index', 'uses' => 'RoleController@index']);
    Route::get('/create', ['as' => 'backend.role.create', 'uses' => 'RoleController@create']);
    Route::post('/store', ['as' => 'backend.role.store', 'uses' => 'RoleController@store']);
    Route::get('/{roleInfo}', ['as' => 'backend.role.edit', 'uses' => 'RoleController@edit']);
    Route::put('/{roleInfo}', ['as' => 'backend.role.update', 'uses' => 'RoleController@update']);
    Route::delete('/destroy/{$roleInfo}', ['as' => 'backend.role.destroy', 'uses' => 'RoleController@destroy']);
    Route::post('/change-status/{status}', ['as' => 'backend.role.changestatus', 'uses' => 'RoleController@changeStatus']);
});
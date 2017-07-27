<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 14:05
 */

Route::group(['prefix' => 'blockip'], function () {
    Route::get('/', ['as' => 'backend.blockip.index', 'uses' => 'BlockIpController@index']);
    Route::get('/create', ['as' => 'backend.blockip.create', 'uses' => 'BlockIpController@create']);
    Route::post('/store', ['as' => 'backend.blockip.store', 'uses' => 'BlockIpController@store']);
    Route::get('/{blockipInfo}', ['as' => 'backend.blockip.edit', 'uses' => 'BlockIpController@edit']);
    Route::put('/{blockipInfo}', ['as' => 'backend.blockip.update', 'uses' => 'BlockIpController@update']);
    Route::delete('/destroy/{blockipInfo}', ['as' => 'backend.blockip.destroy', 'uses' => 'BlockIpController@destroy']);
    Route::post('/change-status/{status}', ['as' => 'backend.blockip.changestatus', 'uses' => 'BlockIpController@changeStatus']);
});
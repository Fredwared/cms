<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 14:05
 */

Route::group(['prefix' => 'log'], function () {
    Route::get('/', ['as' => 'backend.log.index', 'uses' => 'LogController@index']);
    Route::get('/query', ['as' => 'backend.log.query', 'uses' => 'LogController@queryLog']);
    Route::delete('/destroy/{day}/{type?}', ['as' => 'backend.log.destroy', 'uses' => 'LogController@destroy']);
});
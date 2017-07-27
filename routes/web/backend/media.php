<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 13:50
 */

Route::group(['namespace' => 'Media', 'prefix' => 'media'], function () {
    Route::group(['prefix' => 'file'], function () {
        Route::get('/', ['as' => 'backend.media.file.index', 'uses' => 'FileController@index']);
        Route::get('/{fileInfo}', ['as' => 'backend.media.file.edit', 'uses' => 'FileController@edit']);
        Route::get('/download/{fileInfo}', ['as' => 'backend.media.file.download', 'uses' => 'FileController@download']);
    });

    Route::group(['prefix' => 'image'], function () {
        Route::get('/', ['as' => 'backend.media.image.index', 'uses' => 'ImageController@index']);
        Route::get('/{imageInfo}', ['as' => 'backend.media.image.edit', 'uses' => 'ImageController@edit']);
    });

    Route::group(['prefix' => 'video'], function () {
        Route::get('/', ['as' => 'backend.media.video.index', 'uses' => 'VideoController@index']);
        Route::get('/create', ['as' => 'backend.media.video.create', 'uses' => 'VideoController@create']);
        Route::post('/store', ['as' => 'backend.media.video.store', 'uses' => 'VideoController@store']);
        Route::get('/{videoInfo}', ['as' => 'backend.media.video.edit', 'uses' => 'VideoController@edit']);
        Route::put('/{videoInfo}', ['as' => 'backend.media.video.update', 'uses' => 'VideoController@update']);
        Route::delete('/destroy/{videoInfo}', ['as' => 'backend.media.video.destroy', 'uses' => 'VideoController@destroy']);
    });

    Route::delete('/destroy/{mediaInfo}', ['as' => 'backend.media.destroy', 'uses' => 'MediaController@destroy']);
    Route::post('/changestatus/{status}', ['as' => 'backend.media.changestatus', 'uses' => 'MediaController@changeStatus']);
    Route::post('/upload/{type}', ['as' => 'backend.media.upload', 'uses' => 'MediaController@upload']);
    Route::get('/menu/{mediaInfo}', ['as' => 'backend.media.menu', 'uses' => 'MediaController@menu']);
    Route::put('/updatelabel/{mediaInfo}', ['as' => 'backend.media.updatelabel', 'uses' => 'MediaController@updateLabel']);
});
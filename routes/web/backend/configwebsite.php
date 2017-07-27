<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 14:05
 */

Route::group(['namespace' => 'ConfigWebsite', 'prefix' => 'config-website'], function () {
    Route::group(['prefix' => 'config'], function () {
        Route::get('/', ['as' => 'backend.configwebsite.config.index', 'uses' => 'ConfigController@index']);
        Route::get('/create', ['as' => 'backend.configwebsite.config.create', 'uses' => 'ConfigController@create']);
        Route::post('/store', ['as' => 'backend.configwebsite.config.store', 'uses' => 'ConfigController@store']);
        Route::get('/{configInfo}', ['as' => 'backend.configwebsite.config.edit', 'uses' => 'ConfigController@edit']);
        Route::put('/{configInfo}', ['as' => 'backend.configwebsite.config.update', 'uses' => 'ConfigController@update']);
    });

    Route::group(['prefix' => 'translate'], function () {
        Route::get('/', ['as' => 'backend.configwebsite.translate.index', 'uses' => 'TranslateController@index']);
        Route::get('/create', ['as' => 'backend.configwebsite.translate.create', 'uses' => 'TranslateController@create']);
        Route::post('/store', ['as' => 'backend.configwebsite.translate.store', 'uses' => 'TranslateController@store']);
        Route::get('/{translateInfo}', ['as' => 'backend.configwebsite.translate.edit', 'uses' => 'TranslateController@edit']);
        Route::put('/{translateInfo}', ['as' => 'backend.configwebsite.translate.update', 'uses' => 'TranslateController@update']);
        Route::delete('/destroy/{translateInfo}', ['as' => 'backend.configwebsite.translate.destroy', 'uses' => 'TranslateController@destroy']);
        Route::post('/change-status/{status}', ['as' => 'backend.configwebsite.translate.changestatus', 'uses' => 'TranslateController@changeStatus']);
    });

    Route::group(['prefix' => 'slide'], function () {
        Route::get('/', ['as' => 'backend.configwebsite.slide.index', 'uses' => 'SlideController@index']);
        Route::get('/create/{language?}/{type?}', ['as' => 'backend.configwebsite.slide.create', 'uses' => 'SlideController@create']);
        Route::post('/store', ['as' => 'backend.configwebsite.slide.store', 'uses' => 'SlideController@store']);
        Route::get('/{slideInfo}', ['as' => 'backend.configwebsite.slide.edit', 'uses' => 'SlideController@edit']);
        Route::put('/{slideInfo}', ['as' => 'backend.configwebsite.slide.update', 'uses' => 'SlideController@update']);
        Route::delete('/destroy/{slideInfo}', ['as' => 'backend.configwebsite.slide.destroy', 'uses' => 'SlideController@destroy']);
        Route::post('/change-status/{status}', ['as' => 'backend.configwebsite.slide.changestatus', 'uses' => 'SlideController@changeStatus']);
        Route::match(['get', 'put'], '/sort/{language}/{type}', ['as' => 'backend.configwebsite.slide.sort', 'uses' => 'SlideController@sort']);
    });

    Route::group(['prefix' => 'page'], function () {
        Route::get('/', ['as' => 'backend.configwebsite.page.index', 'uses' => 'PageController@index']);
        Route::get('/create/{language?}', ['as' => 'backend.configwebsite.page.create', 'uses' => 'PageController@create']);
        Route::post('/store', ['as' => 'backend.configwebsite.page.store', 'uses' => 'PageController@store']);
        Route::get('/{pageInfo}', ['as' => 'backend.configwebsite.page.edit', 'uses' => 'PageController@edit']);
        Route::put('/{pageInfo}', ['as' => 'backend.configwebsite.page.update', 'uses' => 'PageController@update']);
        Route::delete('/destroy/{pageInfo}', ['as' => 'backend.configwebsite.page.destroy', 'uses' => 'PageController@destroy']);
        Route::post('/change-status/{status}', ['as' => 'backend.configwebsite.page.changestatus', 'uses' => 'PageController@changeStatus']);
    });

    Route::group(['prefix' => 'navigation'], function () {
        Route::get('/', ['as' => 'backend.configwebsite.navigation.index', 'uses' => 'NavigationController@index']);
        Route::post('/store/{language}/{type}', ['as' => 'backend.configwebsite.navigation.store', 'uses' => 'NavigationController@store']);
        Route::put('/update/{navigationInfo}', ['as' => 'backend.configwebsite.navigation.update', 'uses' => 'NavigationController@update']);
        Route::delete('/destroy/{navigationInfo}', ['as' => 'backend.configwebsite.navigation.destroy', 'uses' => 'NavigationController@destroy']);
        Route::post('/sort/{language}', ['as' => 'backend.configwebsite.navigation.sort', 'uses' => 'NavigationController@sort']);
    });
});
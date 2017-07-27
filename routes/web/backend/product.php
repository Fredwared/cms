<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 13:50
 */

Route::group(['namespace' => 'Product', 'prefix' => 'product'], function () {
    Route::group(['prefix' => 'category'], function () {
        Route::get('/', ['as' => 'backend.product.category.index', 'uses' => 'CategoryController@index']);
        Route::get('/create/{language?}', ['as' => 'backend.product.category.create', 'uses' => 'CategoryController@create']);
        Route::post('/store', ['as' => 'backend.product.category.store', 'uses' => 'CategoryController@store']);
        Route::get('/{categoryInfo}', ['as' => 'backend.product.category.edit', 'uses' => 'CategoryController@edit']);
        Route::put('/{categoryInfo}', ['as' => 'backend.product.category.update', 'uses' => 'CategoryController@update']);
        Route::delete('/destroy/{categoryInfo}', ['as' => 'backend.product.category.destroy', 'uses' => 'CategoryController@destroy']);
        Route::post('/change-status/{status}', ['as' => 'backend.product.category.changestatus', 'uses' => 'CategoryController@changeStatus']);
        Route::get('/sort/{language}', ['as' => 'backend.product.category.sort', 'uses' => 'CategoryController@sort']);
    });

    Route::group(['prefix' => 'manufacturer'], function () {
        Route::get('/', ['as' => 'backend.product.manufacturer.index', 'uses' => 'ManufacturerController@index']);
        Route::get('/create/{language?}', ['as' => 'backend.product.manufacturer.create', 'uses' => 'ManufacturerController@create']);
        Route::get('/{manufacturerInfo}', ['as' => 'backend.product.manufacturer.edit', 'uses' => 'ManufacturerController@edit']);
        Route::delete('/destroy/{manufacturerInfo}', ['as' => 'backend.product.manufacturer.destroy', 'uses' => 'ManufacturerController@destroy']);
        Route::post('/change-status/{status}', ['as' => 'backend.product.manufacturer.changestatus', 'uses' => 'ManufacturerController@changeStatus']);
    });

    Route::group(['prefix' => 'type'], function () {
        Route::get('/', ['as' => 'backend.product.spectification.index', 'uses' => 'SpectificationController@index']);
        Route::get('/create/{language?}', ['as' => 'backend.product.spectification.create', 'uses' => 'SpectificationController@create']);
        Route::get('/{typeInfo}', ['as' => 'backend.product.spectification.edit', 'uses' => 'SpectificationController@edit']);
        Route::delete('/destroy/{typeInfo}', ['as' => 'backend.product.spectification.destroy', 'uses' => 'SpectificationController@destroy']);
        Route::post('/change-status/{status}', ['as' => 'backend.product.spectification.changestatus', 'uses' => 'SpectificationController@changeStatus']);
    });

    Route::group(['prefix' => 'promotion'], function () {
        Route::get('/', ['as' => 'backend.product.promotion.index', 'uses' => 'PromotionController@index']);
        Route::get('/create/{language?}', ['as' => 'backend.product.promotion.create', 'uses' => 'PromotionController@create']);
        Route::post('/store', ['as' => 'backend.product.promotion.store', 'uses' => 'PromotionController@store']);
        Route::get('/{promotionInfo}', ['as' => 'backend.product.promotion.edit', 'uses' => 'PromotionController@edit']);
        Route::put('/{promotionInfo}', ['as' => 'backend.product.promotion.update', 'uses' => 'PromotionController@update']);
        Route::delete('/destroy/{promotionInfo}', ['as' => 'backend.product.promotion.destroy', 'uses' => 'PromotionController@destroy']);
        Route::post('/change-status/{status}', ['as' => 'backend.product.promotion.changestatus', 'uses' => 'PromotionController@changeStatus']);
    });

    Route::group(['prefix' => 'build-top'], function () {
        Route::get('/', ['as' => 'backend.product.buildtop.index', 'uses' => 'BuildtopController@index']);
        Route::post('/save', ['as' => 'backend.product.buildtop.save', 'uses' => 'BuildtopController@save']);
        Route::delete('/destroy', ['as' => 'backend.product.buildtop.destroy', 'uses' => 'BuildtopController@destroy']);
        Route::get('/list-product', ['as' => 'backend.product.buildtop.listproduct', 'uses' => 'BuildtopController@listProduct']);
    });

    Route::get('/', ['as' => 'backend.product.index', 'uses' => 'ProductController@index']);
});
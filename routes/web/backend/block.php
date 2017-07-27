<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 13:50
 */

Route::group(['namespace' => 'Block', 'prefix' => 'block'], function () {
    Route::group(['prefix' => 'page'], function () {
        Route::get('/', ['as' => 'backend.block.page.index', 'uses' => 'PageController@index']);
        Route::get('/create', ['as' => 'backend.block.page.create', 'uses' => 'PageController@create']);
        Route::post('/store', ['as' => 'backend.block.page.store', 'uses' => 'PageController@store']);
        Route::get('/{pageInfo}', ['as' => 'backend.block.page.edit', 'uses' => 'PageController@edit']);
        Route::put('/{pageInfo}', ['as' => 'backend.block.page.update', 'uses' => 'PageController@update']);
        Route::delete('/destroy/{pageInfo}', ['as' => 'backend.block.page.destroy', 'uses' => 'PageController@destroy']);

        Route::group(['prefix' => 'layout'], function () {
            Route::get('/{pageInfo}', ['as' => 'backend.block.page.layout.index', 'uses' => 'PageController@layout']);
            Route::get('/template/{pageInfo}', ['as' => 'backend.block.page.layout.template', 'uses' => 'PageController@getTemplate']);
            Route::get('/template/{pageInfo}/{templateInfo}/{widgetInfo?}', ['as' => 'backend.block.page.layout.template.detail', 'uses' => 'PageController@detailTemplate']);
            Route::get('/function/{pageInfo}/{functionInfo}/{widgetInfo?}', ['as' => 'backend.block.page.layout.function.detail', 'uses' => 'PageController@detailFunction']);

            Route::group(['prefix' => 'widget'], function () {
                Route::post('/sort', ['as' => 'backend.block.page.layout.widget.sort', 'uses' => 'PageController@updateSortWidget']);
                Route::post('/store/static/{pageInfo}/{area}', ['as' => 'backend.block.page.layout.widget.store.static', 'uses' => 'PageController@storeWidgetStatic']);
                Route::post('/store/dynamic/{pageInfo}/{area}', ['as' => 'backend.block.page.layout.widget.store.dynamic', 'uses' => 'PageController@storeWidgetDynamic']);
                Route::get('/edit/{widgetInfo}', ['as' => 'backend.block.page.layout.widget.edit', 'uses' => 'PageController@editWidget']);
                Route::put('/update/{widgetInfo}', ['as' => 'backend.block.page.layout.widget.update', 'uses' => 'PageController@updateWidget']);
                Route::delete('/destroy/{widgetInfo}', ['as' => 'backend.block.page.layout.widget.destroy', 'uses' => 'PageController@destroyWidget']);
                Route::post('/change-status/{widgetInfo}', ['as' => 'backend.block.page.layout.widget.changestatus', 'uses' => 'PageController@changeStatusWidget']);
            });
        });
    });

    Route::group(['prefix' => 'function'], function () {
        Route::get('/', ['as' => 'backend.block.function.index', 'uses' => 'FunctionController@index']);
        Route::get('/create', ['as' => 'backend.block.function.create', 'uses' => 'FunctionController@create']);
        Route::post('/store', ['as' => 'backend.block.function.store', 'uses' => 'FunctionController@store']);
        Route::get('/{functionInfo}', ['as' => 'backend.block.function.edit', 'uses' => 'FunctionController@edit']);
        Route::put('/{functionInfo}', ['as' => 'backend.block.function.update', 'uses' => 'FunctionController@update']);
        Route::delete('/destroy/{functionInfo}', ['as' => 'backend.block.function.destroy', 'uses' => 'FunctionController@destroy']);
    });

    Route::group(['prefix' => 'template'], function () {
        Route::get('/', ['as' => 'backend.block.template.index', 'uses' => 'TemplateController@index']);
        Route::get('/create/{type}', ['as' => 'backend.block.template.create', 'uses' => 'TemplateController@create']);
        Route::post('/store/{type}', ['as' => 'backend.block.template.store', 'uses' => 'TemplateController@store']);
        Route::get('/{templateInfo}', ['as' => 'backend.block.template.edit', 'uses' => 'TemplateController@edit']);
        Route::put('/{templateInfo}', ['as' => 'backend.block.template.update', 'uses' => 'TemplateController@update']);
        Route::delete('/destroy/{templateInfo}', ['as' => 'backend.block.template.destroy', 'uses' => 'TemplateController@destroy']);
    });

    Route::group(['prefix' => 'widget'], function () {
        Route::get('/', ['as' => 'backend.block.widget.index', 'uses' => 'WidgetController@index']);
        Route::get('/create', ['as' => 'backend.block.widget.create', 'uses' => 'WidgetController@create']);
        Route::post('/store', ['as' => 'backend.block.widget.store', 'uses' => 'WidgetController@store']);
        Route::get('/{widgetInfo}', ['as' => 'backend.block.widget.edit', 'uses' => 'WidgetController@edit']);
        Route::put('/{widgetInfo}', ['as' => 'backend.block.widget.update', 'uses' => 'WidgetController@update']);
        Route::delete('/destroy/{widgetInfo}', ['as' => 'backend.block.widget.destroy', 'uses' => 'WidgetController@destroy']);
    });
});
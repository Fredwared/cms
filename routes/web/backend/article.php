<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 13:50
 */

Route::group(['namespace' => 'Article', 'prefix' => 'article'], function () {
    Route::group(['prefix' => 'category'], function () {
        Route::get('/', ['as' => 'backend.article.category.index', 'uses' => 'CategoryController@index']);
        Route::get('/create/{language?}', ['as' => 'backend.article.category.create', 'uses' => 'CategoryController@create']);
        Route::post('/store', ['as' => 'backend.article.category.store', 'uses' => 'CategoryController@store']);
        Route::get('/{categoryInfo}', ['as' => 'backend.article.category.edit', 'uses' => 'CategoryController@edit']);
        Route::put('/{categoryInfo}', ['as' => 'backend.article.category.update', 'uses' => 'CategoryController@update']);
        Route::delete('/destroy/{categoryInfo}', ['as' => 'backend.article.category.destroy', 'uses' => 'CategoryController@destroy']);
        Route::post('/change-status/{status}', ['as' => 'backend.article.category.changestatus', 'uses' => 'CategoryController@changeStatus']);
        Route::match(['get', 'put'], '/sort/{language}', ['as' => 'backend.article.category.sort', 'uses' => 'CategoryController@sort']);
    });

    Route::group(['prefix' => 'topic'], function () {
        Route::get('/', ['as' => 'backend.article.topic.index', 'uses' => 'TopicController@index']);
        Route::get('/create/{language?}', ['as' => 'backend.article.topic.create', 'uses' => 'TopicController@create']);
        Route::post('/store', ['as' => 'backend.article.topic.store', 'uses' => 'TopicController@store']);
        Route::get('/{topicInfo}', ['as' => 'backend.article.topic.edit', 'uses' => 'TopicController@edit']);
        Route::put('/{topicInfo}', ['as' => 'backend.article.topic.update', 'uses' => 'TopicController@update']);
        Route::delete('/destroy/{topicInfo}', ['as' => 'backend.article.topic.destroy', 'uses' => 'TopicController@destroy']);
        Route::post('/change-status/{status}', ['as' => 'backend.article.topic.changestatus', 'uses' => 'TopicController@changeStatus']);
    });

    Route::group(['prefix' => 'tag'], function () {
        Route::get('/', ['as' => 'backend.article.tag.index', 'uses' => 'TagController@index']);
        Route::get('/create/{language?}', ['as' => 'backend.article.tag.create', 'uses' => 'TagController@create']);
        Route::post('/store', ['as' => 'backend.article.tag.store', 'uses' => 'TagController@store']);
    });

    Route::group(['prefix' => 'build-top'], function () {
        Route::get('/', ['as' => 'backend.article.buildtop.index', 'uses' => 'BuildtopController@index']);
        Route::post('/save', ['as' => 'backend.article.buildtop.save', 'uses' => 'BuildtopController@save']);
        Route::delete('/destroy', ['as' => 'backend.article.buildtop.destroy', 'uses' => 'BuildtopController@destroy']);
        Route::get('/list-article', ['as' => 'backend.article.buildtop.listarticle', 'uses' => 'BuildtopController@listArticle']);
    });

    Route::get('/', ['as' => 'backend.article.index', 'uses' => 'ArticleController@index']);
    Route::get('/create/{language?}', ['as' => 'backend.article.create', 'uses' => 'ArticleController@create']);
    Route::post('/store', ['as' => 'backend.article.store', 'uses' => 'ArticleController@store']);
    Route::get('/{articleInfo}', ['as' => 'backend.article.edit', 'uses' => 'ArticleController@edit']);
    Route::put('/{articleInfo}', ['as' => 'backend.article.update', 'uses' => 'ArticleController@update']);
    Route::delete('/destroy/{articleInfo}', ['as' => 'backend.article.destroy', 'uses' => 'ArticleController@destroy']);
    Route::post('/change-status/{status}', ['as' => 'backend.article.changestatus', 'uses' => 'ArticleController@changeStatus']);
});
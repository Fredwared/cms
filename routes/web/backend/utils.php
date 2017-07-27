<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 13:50
 */

Route::group(['namespace' => 'Utils', 'prefix' => 'utils'], function () {
    Route::group(['prefix' => 'search'], function () {
        Route::get('/group', ['as' => 'backend.utils.search.group', 'uses' => 'SearchController@group']);
        Route::get('/user', ['as' => 'backend.utils.search.user', 'uses' => 'SearchController@user']);
        Route::get('/medialabel', ['as' => 'backend.utils.search.medialabel', 'uses' => 'SearchController@medialabel']);
        Route::get('/tag', ['as' => 'backend.utils.search.tag', 'uses' => 'SearchController@tag']);
    });

    Route::group(['prefix' => 'crawler'], function () {
        Route::match(['get', 'post'], '/parselink', ['as' => 'backend.utils.crawler.parselink', 'uses' => 'CrawlerController@parseLink']);
    });

    Route::group(['prefix' => 'editor'], function () {
        Route::get('/insertpost', ['as' => 'backend.utils.editor.insertpost', 'uses' => 'EditorController@insertPost']);
        Route::get('/insertvideo', ['as' => 'backend.utils.editor.insertvideo', 'uses' => 'EditorController@insertVideo']);
        Route::get('/insertlink', ['as' => 'backend.utils.editor.insertlink', 'uses' => 'EditorController@insertLink']);
    });

    Route::get('/create-code', ['as' => 'backend.utils.createcode', 'uses' => 'UtilsController@createCode']);
    Route::get('/category', ['as' => 'backend.utils.getcategory', 'uses' => 'UtilsController@getCategory']);
    Route::get('/category-parent', ['as' => 'backend.utils.getcateparent', 'uses' => 'UtilsController@getCateParent']);
    Route::get('/page-parent', ['as' => 'backend.utils.getpageparent', 'uses' => 'UtilsController@getPageParent']);
    Route::get('/source-langmap', ['as' => 'backend.utils.getsourcelangmap', 'uses' => 'UtilsController@getSourceLangMap']);
    Route::get('/article', ['as' => 'backend.utils.getarticle', 'uses' => 'UtilsController@getArticle']);
    Route::match(['get', 'post'], '/import/{table}', ['as' => 'backend.utils.import', 'uses' => 'UtilsController@import']);
    Route::post('/export/{table}', ['as' => 'backend.utils.export', 'uses' => 'UtilsController@export']);
    Route::get('/article-reference', ['as' => 'backend.utils.articlereference', 'uses' => 'UtilsController@articleReference']);
    Route::get('/article-topic', ['as' => 'backend.utils.articletopic', 'uses' => 'UtilsController@articleTopic']);
    Route::get('/video-info', ['as' => 'backend.utils.videoinfo', 'uses' => 'UtilsController@videoInfo']);
    Route::post('/upload', ['as' => 'backend.utils.upload', 'uses' => 'UtilsController@upload']);
});
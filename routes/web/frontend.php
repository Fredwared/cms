<?php

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//Frontend
Route::group(['namespace' => 'Frontend', 'prefix' => LaravelLocalization::setLocale()], function () {
	Route::patterns([
		'cate_code' => '[A-Za-z0-9\-]+',
		'title' => '[A-Za-z0-9\-]+',
		'id' => '[0-9]+'
	]);

	Route::get('/', ['as' => 'homepage', 'uses' => 'IndexController@index']);

	Route::get('/getcaptcha/{config?}', ['as' => 'getcaptcha', function(\Mews\Captcha\Captcha $captcha, $config = 'default') {
		return $captcha->src($config);
	}]);

    Route::group(['namespace' => 'Article', 'prefix' => 'tin-tuc'], function () {
        Route::get('/{title}-{id}.html', ['as' => 'article', 'uses' => 'ArticleController@index']);
        Route::get('/{cate_code}', ['as' => 'category', 'uses' => 'CategoryController@index']);
    });

    Route::group(['namespace' => 'Product', 'prefix' => 'san-pham'], function () {
        Route::get('/{title}-{id}.html', ['as' => 'article', 'uses' => 'ProductController@index']);
        Route::get('/{cate_code}', ['as' => 'category', 'uses' => 'CategoryController@index']);
    });
});

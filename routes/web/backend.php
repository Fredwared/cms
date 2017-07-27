<?php

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

//Backend
Route::group(['namespace' => 'Backend', 'prefix' => 'backend'], function () {
    require (__DIR__ . '/backend/auth.php');

	Route::group(['middleware' => ['auth:backend', 'role']], function () {
		Route::get('/', ['as' => 'backend.index', 'uses' => 'IndexController@index']);

        foreach (glob(__DIR__ . '/backend/*.php') as $routeFile) {
            if (!str_contains($routeFile, 'auth.php')) {
                require $routeFile;
            }
        }
	});
});

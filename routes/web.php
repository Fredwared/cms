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

Route::group(['middleware' => ['blockip']], function () {
    foreach (glob(__DIR__ . '/web/*.php') as $routeFile) {
        require $routeFile;
    }
});

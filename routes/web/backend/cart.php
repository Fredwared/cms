<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 13:50
 */

Route::group(['namespace' => 'Cart', 'prefix' => 'cart'], function () {
    Route::group(['prefix' => 'customer'], function () {
        Route::get('/', ['as' => 'backend.cart.customer.index', 'uses' => 'CustomerController@index']);
    });

    Route::group(['prefix' => 'order'], function () {
        Route::get('/', ['as' => 'backend.cart.order.index', 'uses' => 'OrderController@index']);
    });

    Route::group(['prefix' => 'payment'], function () {
        Route::get('/', ['as' => 'backend.cart.payment.index', 'uses' => 'PaymentController@index']);
    });
});
<?php
/**
 * Created by PhpStorm.
 * User: tiendq
 * Date: 28/09/2016
 * Time: 14:05
 */

Route::group(['namespace' => 'Location', 'prefix' => 'location'], function () {
    Route::get('/', ['as' => 'backend.location.index', 'uses' => 'CountryController@index']);

    Route::group(['prefix' => 'country'], function () {
        Route::get('/', ['as' => 'backend.location.country.index', 'uses' => 'CountryController@index']);
        Route::get('/create', ['as' => 'backend.location.country.create', 'uses' => 'CountryController@create']);
        Route::post('/store', ['as' => 'backend.location.country.store', 'uses' => 'CountryController@store']);
        Route::get('/edit/{countryInfo}', ['as' => 'backend.location.country.edit', 'uses' => 'CountryController@edit']);
        Route::put('/update/{countryInfo}', ['as' => 'backend.location.country.update', 'uses' => 'CountryController@update']);
        Route::delete('/destroy/{countryInfo}', ['as' => 'backend.location.country.destroy', 'uses' => 'CountryController@destroy']);
        Route::post('/sort', ['as' => 'backend.location.country.sort', 'uses' => 'CountryController@updateSort']);
    });

    Route::group(['prefix' => 'city'], function () {
        Route::get('/create/{countryInfo}', ['as' => 'backend.location.city.create', 'uses' => 'CityController@create']);
        Route::post('/store', ['as' => 'backend.location.city.store', 'uses' => 'CityController@store']);
        Route::get('/edit/{cityInfo}', ['as' => 'backend.location.city.edit', 'uses' => 'CityController@edit']);
        Route::put('/update/{cityInfo}', ['as' => 'backend.location.city.update', 'uses' => 'CityController@update']);
        Route::delete('/destroy/{cityInfo}', ['as' => 'backend.location.city.destroy', 'uses' => 'CityController@destroy']);
        Route::get('/{countryInfo}', ['as' => 'backend.location.city.index', 'uses' => 'CityController@index']);
        Route::post('/sort', ['as' => 'backend.location.city.sort', 'uses' => 'CityController@updateSort']);
    });

    Route::group(['prefix' => 'district'], function () {
        Route::get('/create/{cityInfo}', ['as' => 'backend.location.district.create', 'uses' => 'DistrictController@create']);
        Route::post('/store', ['as' => 'backend.location.district.store', 'uses' => 'DistrictController@store']);
        Route::get('/edit/{districtInfo}', ['as' => 'backend.location.district.edit', 'uses' => 'DistrictController@edit']);
        Route::put('/update/{districtInfo}', ['as' => 'backend.location.district.update', 'uses' => 'DistrictController@update']);
        Route::delete('/destroy/{districtInfo}', ['as' => 'backend.location.district.destroy', 'uses' => 'DistrictController@destroy']);
        Route::get('/{cityInfo}', ['as' => 'backend.location.district.index', 'uses' => 'DistrictController@index']);
        Route::post('/sort', ['as' => 'backend.location.district.sort', 'uses' => 'DistrictController@updateSort']);
    });

    Route::group(['prefix' => 'ward'], function () {
        Route::get('/create/{districtInfo}', ['as' => 'backend.location.ward.create', 'uses' => 'WardController@create']);
        Route::post('/store', ['as' => 'backend.location.ward.store', 'uses' => 'WardController@store']);
        Route::get('/edit/{wardInfo}', ['as' => 'backend.location.ward.edit', 'uses' => 'WardController@edit']);
        Route::put('/update/{wardInfo}', ['as' => 'backend.location.ward.update', 'uses' => 'WardController@update']);
        Route::delete('/destroy/{wardInfo}', ['as' => 'backend.location.ward.destroy', 'uses' => 'WardController@destroy']);
        Route::get('/{districtInfo}', ['as' => 'backend.location.ward.index', 'uses' => 'WardController@index']);
        Route::post('/sort', ['as' => 'backend.location.ward.sort', 'uses' => 'WardController@updateSort']);
    });
});
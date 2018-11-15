<?php

Route::group(['namespace' => 'API'], function () {
    Route::group(['prefix' => 'doctor/v1', 'namespace' => 'DOCTOR\V1'], function () {

        Route::group(['middleware' => []], function () {
            // Authentication
            Route::post('signin', 'AuthController@signIn');
            Route::post('quickblox/getTokenAuth', 'QuickbloxController@getTokenAuth');
            Route::post('quickblox/signup', 'QuickbloxController@signUp');
            Route::post('quickblox/signin', 'QuickbloxController@signIn');
            Route::post('signup', 'AuthController@signUp');
            Route::post('token/refresh', 'AuthController@refreshToken');
        });

        Route::group(['middleware' => ['api.client', 'api.user']], function () {
            Route::resource('articles', 'ArticleController');
            Route::group(['prefix' => 'me'], function () {
                Route::get('/', 'MeController@getMe');
                Route::post('/update', 'MeController@update');
            });
            Route::group(['prefix' => 'clinics'], function () {
                Route::post('/store', 'ClinicController@store');
            });

            Route::post('signout', 'AuthController@postSignOut');
        });
    });
});

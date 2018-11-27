<?php

Route::group(['namespace' => 'API'], function () {
    Route::group(['prefix' => 'v1', 'namespace' => 'V1'], function () {

        Route::group(['middleware' => []], function () {
            // Authentication
            Route::post('quickblox/getTokenAuth', 'QuickbloxController@getTokenAuth');
            Route::post('quickblox/signup', 'QuickbloxController@signUp');
            Route::post('quickblox/signin', 'QuickbloxController@signIn');
            Route::get('nganluong', 'TestController@index');
        });

    });
    Route::group(['prefix' => 'doctor/v1', 'namespace' => 'DOCTOR\V1'], function () {

        Route::group(['middleware' => ['api.provider.doctor']], function () {
            // Authentication
            Route::post('signin', 'AuthController@signIn');
            Route::post('signup', 'AuthController@signUp');
            Route::post('token/refresh', 'AuthController@refreshToken');
        });

        Route::group(['middleware' => ['api.client', 'api.user']], function () {
            Route::get('/detail', 'DoctorController@detail');
            Route::group(['prefix' => 'me'], function () {
                Route::get('/', 'MeController@getMe');
                Route::post('/update', 'MeController@update');
            });
            Route::group(['prefix' => 'clinics'], function () {
                Route::post('/store', 'ClinicController@store');
            });
            Route::group(['prefix' => 'plans'], function () {
                Route::get('/list/{day}', 'PlanController@index');
                Route::get('/order', 'PlanController@order');
                Route::get('/create/{day}', 'PlanController@create');
                Route::post('/store', 'PlanController@store');

            });

            Route::post('signout', 'AuthController@postSignOut');
        });
    });

    Route::group(['prefix' => 'patient/v1', 'namespace' => 'PATIENT\V1'], function () {

        Route::group(['middleware' => ['api.provider.patient']], function () {
            // Authentication
            Route::post('signin', 'AuthController@signIn');
            Route::post('signup', 'AuthController@signUp');
            Route::post('token/refresh', 'AuthController@refreshToken');
        });

        Route::group(['middleware' => ['api.client', 'api.user']], function () {
            Route::group(['prefix' => 'search-doctor'], function () {
                Route::get('/', 'DoctorController@index');
            });
            Route::group(['prefix' => 'doctor'], function () {
                Route::get('/detail/{idDoctor}', 'DoctorController@detail');
            });
            Route::group(['prefix' => 'plans'], function () {
                Route::get('/list/{idDoctor}/{day}', 'PlanController@index');
                Route::get('/order', 'PlanController@order');
                Route::post('/update-order', 'PlanController@updateOrder');

            });
            Route::post('signout', 'AuthController@postSignOut');
        });
    });
});

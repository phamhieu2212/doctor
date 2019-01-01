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
            Route::group(['prefix' => 'profile'], function () {
                Route::get('/', 'ProfileController@index');
            });
            Route::group(['prefix' => 'price'], function () {
                Route::post('/update', 'PriceController@update');
            });
            Route::group(['prefix' => 'image'], function () {
                Route::post('/update-avatar', 'ImageUploadController@avatar');
            });
            Route::group(['prefix' => 'clinics'], function () {
                Route::get('/', 'ClinicController@index');
                Route::post('/store', 'ClinicController@store');
                Route::post('/update/{id}', 'ClinicController@update');
                Route::get('/edit/{id}/{timestamp}', 'ClinicController@edit');
                Route::get('delete/{id}', 'ClinicController@delete');
            });
            Route::group(['prefix' => 'plans'], function () {
                Route::get('/list', 'PlanController@index');
                Route::get('/order', 'PlanController@order');
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
            Route::post('check-signup', 'AuthController@checkSignUp');
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
                Route::get('/list/{idClinic}/{timestamp}', 'PlanController@index');
                Route::get('/order', 'PlanController@order');

            });
            Route::group(['prefix' => 'point'], function() {
                Route::post('add', 'PatientController@addPoint');
            });

            Route::post('signout', 'AuthController@postSignOut');
        });
    });
});

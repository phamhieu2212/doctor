<?php

Route::group(['namespace' => 'API'], function () {
//    Route::get('/nganluong', 'PATIENT\V1\PaymentController@index');
//    Route::get('/patient/v1/payment/success', 'PATIENT\V1\PaymentController@success');
    Route::group(['prefix' => 'v1', 'namespace' => 'V1'], function () {

        Route::group(['middleware' => []], function () {
            // Authentication
            Route::post('quickblox/getTokenAuth', 'QuickbloxController@getTokenAuth');
            Route::post('quickblox/signup', 'QuickbloxController@signUp');
            Route::post('quickblox/signin', 'QuickbloxController@signIn');
            Route::get('provinces', 'StaticDataController@provinces');
            Route::get('districts/{province_id}', 'StaticDataController@districts');
            Route::post('device', 'DeviceController@register');
            Route::post('image/upload', 'ImageController@upload');
            Route::delete('image/{id}', 'ImageController@delete');
        });

    });
    Route::group(['prefix' => 'doctor/v1', 'namespace' => 'DOCTOR\V1'], function () {

        Route::group(['middleware' => ['api.provider.doctor']], function () {
            // Authentication
            Route::post('signin', 'AuthController@signIn');
            Route::post('signup', 'AuthController@signUp');
            Route::post('token/refresh', 'AuthController@refreshToken');
        });

        Route::group(['middleware' => ['api.client', 'api.user','api.provider.doctor']], function () {
            Route::get('/detail', 'DoctorController@detail');
            Route::group(['prefix' => 'me'], function () {
                Route::get('/', 'MeController@getMe');
                Route::get('/point', 'MeController@getPoint');
                Route::post('/change-password', 'MeController@changePassword');
                Route::get('/logout', 'MeController@logout');
            });
            Route::group(['prefix' => 'profile'], function () {
                Route::get('/', 'ProfileController@index');
                Route::get('/list-data', 'ProfileController@listData');
                Route::post('/update', 'ProfileController@update');
            });
            Route::group(['prefix' => 'price'], function () {
                Route::post('/update', 'PriceController@update');
            });

            Route::group(['prefix' => 'statistic'], function () {
                Route::get('/', 'StatisticController@index');
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
            Route::group(['prefix' => 'patient-file'], function () {
                Route::get('/{idFilePatient}', 'ContactController@getFilePatient');

            });

            Route::group(['prefix' => 'contact'], function () {
                Route::get('/', 'ContactController@index');
                Route::get('/detail/{idPatient}', 'ContactController@detail');

            });
            Route::group(['prefix' => 'chat'], function() {
                Route::get('start-chat', 'ChatController@startChat');
            });

            Route::group(['prefix' => 'call'], function () {
                Route::post('/', 'CallController@call');
                Route::put('/update-state/{call_id}', 'CallController@update');
                Route::get('/check-read', 'CallController@checkRead');
                Route::get('/history', 'CallController@history');
            });
            Route::group(['prefix' => 'call-history'], function () {
                Route::get('/', 'CallHistoryController@index');
                Route::get('/check-read', 'CallHistoryController@checkRead');
                Route::post('/create', 'CallHistoryController@store');
                Route::post('/update-type', 'CallHistoryController@updateType');
                Route::post('/update-endtime', 'CallHistoryController@updateEndtime');
            });

            Route::group(['prefix' => 'notification'], function() {
                Route::get('/list', 'NotificationController@index');
                Route::get('/{id}', 'NotificationController@details');
            });

            Route::post('avatar', 'DoctorController@uploadAvatar');

            Route::post('signout', 'AuthController@postSignOut');
        });
    });

    Route::group(['prefix' => 'patient/v1', 'namespace' => 'PATIENT\V1'], function () {

        Route::group(['middleware' => ['api.provider.patient']], function () {
            // Authentication
            Route::post('signin', 'AuthController@signIn');
            Route::post('token/refresh', 'AuthController@refreshToken');
        });

        Route::group(['middleware' => ['api.client', 'api.user','api.provider.patient']], function () {
            Route::group(['prefix' => 'me'], function () {
                Route::get('/', 'MeController@getMe');
                Route::get('/logout', 'MeController@logout');
                Route::get('/point', 'MeController@getPoint');
            });
            Route::group(['prefix' => 'rate'], function () {
                Route::post('/update', 'RateController@update');
                Route::get('/{idDoctor}', 'RateController@index');
            });
            Route::group(['prefix' => 'search-doctor'], function () {
                Route::get('/', 'DoctorController@index');
            });
            Route::group(['prefix' => 'doctor'], function () {
                Route::get('/detail/{idDoctor}', 'DoctorController@detail');
            });
            Route::group(['prefix' => 'specialty'], function () {
                Route::get('/', 'SpecialtyController@index');
            });
            Route::group(['prefix' => 'call'], function () {
                Route::get('/getTime/{idDoctor}', 'CallController@getTimeCall');
            });
            Route::group(['prefix' => 'patient-file'], function () {
                Route::get('/', 'PatientFileController@index');
                Route::post('/store', 'PatientFileController@store');
                Route::post('/update/{idFilePatient}', 'PatientFileController@update');
                Route::get('delete/{idFilePatient}', 'PatientFileController@delete');
            });
            Route::group(['prefix' => 'call-history'], function () {
                Route::get('/', 'CallHistoryController@index');
                Route::get('/check-read', 'CallHistoryController@checkRead');
                Route::post('/create', 'CallHistoryController@store');
                Route::post('/update-endtime', 'CallHistoryController@updateEndtime');
                Route::post('/update-type', 'CallHistoryController@updateType');
            });

            Route::group(['prefix' => 'history'], function () {
                Route::get('/', 'HistoryController@index');
            });
            Route::group(['prefix' => 'plans'], function () {
                Route::get('/list/{idClinic}/{timestamp}', 'PlanController@index');
                Route::get('/order', 'PlanController@order');

            });
            Route::group(['prefix' => 'point'], function() {
                Route::post('add', 'ChatController@addPoint');
            });

            Route::group(['prefix' => 'chat'], function() {
                Route::get('check-state', 'ChatController@checkChatState');
                Route::get('start-chat', 'ChatController@startChat');
                Route::post('/send-file', 'ChatController@sendFile');
            });

            Route::group(['prefix' => 'notification'], function() {
                Route::get('/list', 'NotificationController@index');
                Route::get('/{id}', 'NotificationController@details');
            });
            
            Route::group(['prefix' => 'payment'], function() {
                Route::post('/send-order', 'PaymentController@index');

            });
            
            Route::get('profile', 'PatientController@show');
            Route::post('avatar', 'PatientController@uploadAvatar');
           
            Route::post('profile', 'PatientController@update');

            Route::post('signout', 'AuthController@postSignOut');
        });
    });
});

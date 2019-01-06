<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(
    App\Models\User::class,
    function (Faker\Generator $faker) {
        return [
            'name'                 => $faker->name,
            'email'                => $faker->email,
            'password'             => bcrypt(str_random(10)),
            'remember_token'       => str_random(10),
            'gender'               => 1,
            'telephone'            => $faker->phoneNumber,
            'birthday'             => $faker->date('Y-m-d'),
            'locale'               => $faker->languageCode,
            'address'              => $faker->address,
            'last_notification_id' => 0,
            'api_access_token'     => '',
            'profile_image_id'     => 0,
            'is_activated'         => 0,
        ];
    }
);

$factory->define(
    App\Models\AdminUser::class,
    function (Faker\Generator $faker) {
        return [
            'name'                 => $faker->name,
            'email'                => $faker->email,
            'password'             => bcrypt(str_random(10)),
            'remember_token'       => str_random(10),
            'locale'               => $faker->languageCode,
            'last_notification_id' => 0,
            'api_access_token'     => '',
            'profile_image_id'     => 0,
        ];
    }
);

$factory->define(
    App\Models\AdminUserRole::class,
    function (Faker\Generator $faker) {
        return [
            'admin_user_id' => $faker->randomNumber(),
            'role'          => 'supper_user',
        ];
    }
);

$factory->define(
    App\Models\SiteConfiguration::class,
    function (Faker\Generator $faker) {
        return [
            'locale'                => 'ja',
            'name'                  => $faker->name,
            'title'                 => $faker->sentence,
            'keywords'              => implode(
                ',',
                $faker->words(5)
            ),
            'description'           => $faker->sentences(
                3,
                true
            ),
            'ogp_image_id'          => 0,
            'twitter_card_image_id' => 0,
        ];
    }
);

$factory->define(
    App\Models\Image::class,
    function (Faker\Generator $faker) {
        return [
            'url'                => $faker->imageUrl(),
            'title'              => $faker->sentence,
            'is_local'           => false,
            'entity_type'        => $faker->word,
            'entity_id'          => 0,
            'file_category_type' => $faker->word,
            's3_key'             => $faker->word,
            's3_bucket'          => $faker->word,
            's3_region'          => $faker->word,
            's3_extension'       => 'png',
            'media_type'         => 'image/png',
            'format'             => 'png',
            'file_size'          => 0,
            'width'              => 100,
            'height'             => 100,
            'is_enabled'         => true,
        ];
    }
);

$factory->define(
    App\Models\Article::class,
    function (Faker\Generator $faker) {
        return [
            'slug'               => $faker->word,
            'title'              => $faker->sentence,
            'keywords'           => implode(
                ',',
                $faker->words(5)
            ),
            'description'        => $faker->sentences(
                3,
                true
            ),
            'content'            => $faker->sentences(
                3,
                true
            ),
            'cover_image_id'     => 0,
            'locale'             => 'ja',
            'is_enabled'         => true,
            'publish_started_at' => $faker->dateTime->format('Y-m-d H:i:s'),
            'publish_ended_at'   => $faker->dateTime->format('Y-m-d H:i:s'),
        ];
    }
);

$factory->define(
    App\Models\UserNotification::class,
    function (Faker\Generator $faker) {
        return [
            'user_id'       => \App\Models\UserNotification::BROADCAST_USER_ID,
            'category_type' => \App\Models\UserNotification::CATEGORY_TYPE_APPLICATION,
            'type'          => \App\Models\UserNotification::TYPE_NOTIFICATION,
            'data'          => '',
            'locale'        => 'en',
            'content'       => 'TEST',
            'read'          => false,
            'sent_at'       => $faker->dateTime,
        ];
    }
);

$factory->define(
    App\Models\AdminUserNotification::class,
    function (Faker\Generator $faker) {
        return [
            'user_id'       => \App\Models\AdminUserNotification::BROADCAST_USER_ID,
            'category_type' => \App\Models\AdminUserNotification::CATEGORY_TYPE_APPLICATION,
            'type'          => \App\Models\AdminUserNotification::TYPE_NOTIFICATION,
            'data'          => '',
            'locale'        => 'en',
            'content'       => 'TEST',
            'read'          => false,
            'sent_at'       => $faker->dateTime,
        ];
    }
);

$factory->define(
    App\Models\OauthClient::class,
    function (Faker\Generator $faker) {
        return [
            'user_id'                => 1,
            'name'                   => $faker->name,
            'secret'                 => $faker->password,
            'redirect'               => $faker->url,
            'personal_access_client' => $faker->boolean,
            'password_client'        => $faker->boolean,
            'revoked'                => $faker->boolean,
        ];
    }
);

$factory->define(App\Models\Hospital::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'name' => '',
        'address' => '',
        'phone' => '',
    ];
});

$factory->define(App\Models\Specialty::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'name' => '',
    ];
});

$factory->define(App\Models\Doctor::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'admin_user_id' => '',
        'hospital_id' => '',
        'gender' => '',
        'telephone' => '',
        'birthday' => '',
        'address' => '',
        'city' => '',
        'position' => '',
        'experience' => '',
        'description' => '',
    ];
});

$factory->define(App\Models\DoctorSpecialty::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'admin_user_id' => '',
        'specialty_id' => '',
    ];
});

$factory->define(App\Models\Clinic::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'admin_user_id' => '',
        'name' => '',
        'address' => '',
        'status' => '',
    ];
});

$factory->define(App\Models\Plan::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'admin_user_id' => '',
        'user_id' => '',
        'price' => '',
        'status' => '',
        'started_at' => '',
        'ended_at' => '',
    ];
});

$factory->define(App\Models\PointDoctor::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'admin_user_id' => '',
        'point' => '',
    ];
});

$factory->define(App\Models\PointPatient::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'user_id' => '',
        'point' => '',
    ];
});

$factory->define(App\Models\ChatHistory::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'user_id' => '',
        'admin_user_id' => '',
    ];
});

$factory->define(App\Models\CallHistory::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'user_id' => '',
        'admin_user_id' => '',
        'start_time' => '',
        'end_time' => '',
        'type' => '',
        'is_read' => '',
    ];
});

$factory->define(App\Models\FilePatient::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'name' => '',
        'title' => '',
        'user_id' => '',
        'started_at' => '',
        'description' => '',
    ];
});

$factory->define(App\Models\FilePatientImage::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'file_patient_id' => '',
        'image_id' => '',
        'type' => '',
    ];
});

$factory->define(App\Models\Patient::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'user_id' => '',
        'full_name' => '',
        'birth_day' => '',
        'gender' => '',
        'identification' => '',
        'country' => '',
        'nation' => '',
        'job' => '',
        'phone_number' => '',
        'email' => '',
        'province' => '',
        'district' => '',
        'ward' => '',
        'address' => '',
        'name_of_relatives' => '',
        'relations' => '',
        'phone_of_relatives' => '',
    ];
});

$factory->define(App\Models\Device::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'device_id' => '',
        'type' => '',
        'user_id' => '',
    ];
});

/* NEW MODEL FACTORY */

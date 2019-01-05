<?php

namespace App\Presenters;

use Illuminate\Support\Facades\Redis;
use App\Models\User;

class PatientPresenter extends BasePresenter
{
    protected $multilingualFields = [];

    protected $imageFields = [];

    /**
    * @return \App\Models\User
    * */
    public function user()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('UserModel');
            $cached = Redis::hget($cacheKey, $this->entity->user_id);

            if( $cached ) {
                $user = new User(json_decode($cached, true));
                $user['id'] = json_decode($cached, true)['id'];
                return $user;
            } else {
                $user = $this->entity->user;
                Redis::hsetnx($cacheKey, $this->entity->user_id, $user);
                return $user;
            }
        }

        $user = $this->entity->user;
        return $user;
    }

    public function profileImage()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('ImageModel');
            $cached = Redis::hget($cacheKey, $this->entity->profile_image_id);

            if( $cached ) {
                $image = new Image(json_decode($cached, true));
                $image['id'] = json_decode($cached, true)['id'];
                return $image;
            } else {
                $image = $this->entity->profileImage;
                Redis::hsetnx($cacheKey, $this->entity->profile_image_id, $image);
                return $image;
            }
        }

        $image = $this->entity->profileImage;
        return $image;
    }

    
}

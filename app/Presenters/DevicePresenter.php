<?php

namespace App\Presenters;

use Illuminate\Support\Facades\Redis;
use App\Models\Device;
use App\Models\User;

class DevicePresenter extends BasePresenter
{
    protected $multilingualFields = [];

    protected $imageFields = [];

    /**
    * @return \App\Models\Device
    * */
    public function device()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('DeviceModel');
            $cached = Redis::hget($cacheKey, $this->entity->device_id);

            if( $cached ) {
                $device = new Device(json_decode($cached, true));
                $device['id'] = json_decode($cached, true)['id'];
                return $device;
            } else {
                $device = $this->entity->device;
                Redis::hsetnx($cacheKey, $this->entity->device_id, $device);
                return $device;
            }
        }

        $device = $this->entity->device;
        return $device;
    }

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

    
}

<?php

namespace App\Presenters;

use Illuminate\Support\Facades\Redis;
use App\Models\User;

class PaymentPresenter extends BasePresenter
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

    
}

<?php

namespace App\Presenters;

use Illuminate\Support\Facades\Redis;
use App\Models\AdminUser;
use App\Models\Specialty;

class DoctorSpecialtyPresenter extends BasePresenter
{
    protected $multilingualFields = [];

    protected $imageFields = [];

    /**
    * @return \App\Models\AdminUser
    * */
    public function adminUser()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('AdminUserModel');
            $cached = Redis::hget($cacheKey, $this->entity->admin_user_id);

            if( $cached ) {
                $adminUser = new AdminUser(json_decode($cached, true));
                $adminUser['id'] = json_decode($cached, true)['id'];
                return $adminUser;
            } else {
                $adminUser = $this->entity->adminUser;
                Redis::hsetnx($cacheKey, $this->entity->admin_user_id, $adminUser);
                return $adminUser;
            }
        }

        $adminUser = $this->entity->adminUser;
        return $adminUser;
    }

    /**
    * @return \App\Models\Specialty
    * */
    public function specialty()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('SpecialtyModel');
            $cached = Redis::hget($cacheKey, $this->entity->specialty_id);

            if( $cached ) {
                $specialty = new Specialty(json_decode($cached, true));
                $specialty['id'] = json_decode($cached, true)['id'];
                return $specialty;
            } else {
                $specialty = $this->entity->specialty;
                Redis::hsetnx($cacheKey, $this->entity->specialty_id, $specialty);
                return $specialty;
            }
        }

        $specialty = $this->entity->specialty;
        return $specialty;
    }

    
}

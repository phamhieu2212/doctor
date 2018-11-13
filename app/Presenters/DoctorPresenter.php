<?php

namespace App\Presenters;

use Illuminate\Support\Facades\Redis;
use App\Models\AdminUser;
use App\Models\Hospital;

class DoctorPresenter extends BasePresenter
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
    * @return \App\Models\Hospital
    * */
    public function hospital()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('HospitalModel');
            $cached = Redis::hget($cacheKey, $this->entity->hospital_id);

            if( $cached ) {
                $hospital = new Hospital(json_decode($cached, true));
                $hospital['id'] = json_decode($cached, true)['id'];
                return $hospital;
            } else {
                $hospital = $this->entity->hospital;
                Redis::hsetnx($cacheKey, $this->entity->hospital_id, $hospital);
                return $hospital;
            }
        }

        $hospital = $this->entity->hospital;
        return $hospital;
    }

    
}

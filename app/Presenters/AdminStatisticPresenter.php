<?php

namespace App\Presenters;

use Illuminate\Support\Facades\Redis;
use App\Models\AdminUser;
use App\Models\Conversation;

class AdminStatisticPresenter extends BasePresenter
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
    * @return \App\Models\Conversation
    * */
    public function conversation()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('ConversationModel');
            $cached = Redis::hget($cacheKey, $this->entity->conversation_id);

            if( $cached ) {
                $conversation = new Conversation(json_decode($cached, true));
                $conversation['id'] = json_decode($cached, true)['id'];
                return $conversation;
            } else {
                $conversation = $this->entity->conversation;
                Redis::hsetnx($cacheKey, $this->entity->conversation_id, $conversation);
                return $conversation;
            }
        }

        $conversation = $this->entity->conversation;
        return $conversation;
    }

    
}

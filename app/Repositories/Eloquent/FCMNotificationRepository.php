<?php namespace App\Repositories\Eloquent;

use \App\Repositories\FCMNotificationRepositoryInterface;
use \App\Models\FCMNotification;

class FCMNotificationRepository extends SingleKeyModelRepository implements FCMNotificationRepositoryInterface
{

    public function getBlankModel()
    {
        return new FCMNotification();
    }

    public function getWithPaginate($uType, $uId, $order, $direction, $offset, $limit)
    {
        return $this->getBlankModel()->where('user_type', $uType)
                                    ->where('user_id', $uId)
                                    ->offset($offset)->limit($limit)
                                    ->orderBy($order, $direction)
                                    ->get();
                                    
    }

}

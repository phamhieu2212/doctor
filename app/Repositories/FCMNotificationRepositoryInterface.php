<?php namespace App\Repositories;

interface FCMNotificationRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getWithPaginate($uType, $uId, $order, $direction, $offset, $limit);
}

<?php

namespace App\Repositories;

interface UserRepositoryInterface extends AuthenticatableRepositoryInterface
{
    public function getByFilterWithListId($idListUserContact,$filter, $order, $direction, $offset, $limit);
}

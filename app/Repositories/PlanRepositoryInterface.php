<?php namespace App\Repositories;

interface PlanRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getOrderByDoctor($idDoctor, $order, $direction, $offset, $limit);

    /**
     * @param array $filter
     *
     * @return int
     */
    public function countOrderByDoctor($idDoctor);
}
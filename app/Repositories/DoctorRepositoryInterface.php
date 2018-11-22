<?php namespace App\Repositories;

interface DoctorRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getByFilter($filter, $order, $direction, $offset, $limit);

    /**
     * @param array $filter
     *
     * @return int
     */
    public function countByFilter($filter);
}
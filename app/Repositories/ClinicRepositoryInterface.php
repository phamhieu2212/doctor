<?php namespace App\Repositories;

interface ClinicRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getByFilterWithAdminUser($adminUser,$filter, $order, $direction, $offset, $limit);

    public function countByFilterWithAdminUser($adminUser,$filter);
}
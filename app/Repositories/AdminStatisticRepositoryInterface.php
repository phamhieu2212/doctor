<?php namespace App\Repositories;

interface AdminStatisticRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function sumAllWithFilter($startDate,$endDate);
}
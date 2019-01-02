<?php namespace App\Repositories;

interface CallHistoryRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getByFilterWithPatient($idPatient,$filter, $order, $direction, $offset, $limit);
}
<?php namespace App\Repositories;

interface CallHistoryRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getByFilterWithPatient($idPatient,$filter, $order, $direction, $offset, $limit);
    public function getByFilterWithDoctor($idDoctor,$filter, $order, $direction, $offset, $limit);
    public function checkRead($doctorId);
    public function updateIsRead();
    public function countAllWithFilter($startDate,$endDate);
}
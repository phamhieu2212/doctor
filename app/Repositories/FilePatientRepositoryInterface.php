<?php namespace App\Repositories;

interface FilePatientRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getByFilterWithPatient($patient,$filter, $order, $direction, $offset, $limit);
}
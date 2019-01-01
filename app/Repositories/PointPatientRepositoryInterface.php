<?php namespace App\Repositories;

interface PointPatientRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function prepareForStart($currentPatient, $doctor);
}
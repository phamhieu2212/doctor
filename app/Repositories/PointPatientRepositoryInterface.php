<?php namespace App\Repositories;

interface PointPatientRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function prepareForStart($adminUserId,$currentPatient, $doctor);
}
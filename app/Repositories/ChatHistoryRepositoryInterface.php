<?php namespace App\Repositories;

interface ChatHistoryRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getLastSession($doctorId, $patientId);
}

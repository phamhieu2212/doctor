<?php namespace App\Repositories;

interface PaymentRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function sumAllWithFilter($startDate,$endDate);
}
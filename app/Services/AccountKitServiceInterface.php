<?php namespace App\Services;

interface AccountKitServiceInterface extends BaseServiceInterface
{
    public function getNumber($token);
}
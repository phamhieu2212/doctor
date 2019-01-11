<?php namespace Tests\Services;

use Tests\TestCase;

class AccountKitServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\AccountKitServiceInterface $service */
        $service = \App::make(\App\Services\AccountKitServiceInterface::class);
        $this->assertNotNull($service);
    }

}

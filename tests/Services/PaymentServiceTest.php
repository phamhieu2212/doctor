<?php namespace Tests\Services;

use Tests\TestCase;

class PaymentServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\PaymentServiceInterface $service */
        $service = \App::make(\App\Services\PaymentServiceInterface::class);
        $this->assertNotNull($service);
    }

}

<?php namespace Tests\Repositories;

use App\Models\Payment;
use Tests\TestCase;

class PaymentRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\PaymentRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PaymentRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $payments = factory(Payment::class, 3)->create();
        $paymentIds = $payments->pluck('id')->toArray();

        /** @var  \App\Repositories\PaymentRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PaymentRepositoryInterface::class);
        $this->assertNotNull($repository);

        $paymentsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Payment::class, $paymentsCheck[0]);

        $paymentsCheck = $repository->getByIds($paymentIds);
        $this->assertEquals(3, count($paymentsCheck));
    }

    public function testFind()
    {
        $payments = factory(Payment::class, 3)->create();
        $paymentIds = $payments->pluck('id')->toArray();

        /** @var  \App\Repositories\PaymentRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PaymentRepositoryInterface::class);
        $this->assertNotNull($repository);

        $paymentCheck = $repository->find($paymentIds[0]);
        $this->assertEquals($paymentIds[0], $paymentCheck->id);
    }

    public function testCreate()
    {
        $paymentData = factory(Payment::class)->make();

        /** @var  \App\Repositories\PaymentRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PaymentRepositoryInterface::class);
        $this->assertNotNull($repository);

        $paymentCheck = $repository->create($paymentData->toFillableArray());
        $this->assertNotNull($paymentCheck);
    }

    public function testUpdate()
    {
        $paymentData = factory(Payment::class)->create();

        /** @var  \App\Repositories\PaymentRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PaymentRepositoryInterface::class);
        $this->assertNotNull($repository);

        $paymentCheck = $repository->update($paymentData, $paymentData->toFillableArray());
        $this->assertNotNull($paymentCheck);
    }

    public function testDelete()
    {
        $paymentData = factory(Payment::class)->create();

        /** @var  \App\Repositories\PaymentRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PaymentRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($paymentData);

        $paymentCheck = $repository->find($paymentData->id);
        $this->assertNull($paymentCheck);
    }

}

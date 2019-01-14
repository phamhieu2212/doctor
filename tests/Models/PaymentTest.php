<?php namespace Tests\Models;

use App\Models\Payment;
use Tests\TestCase;

class PaymentTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Payment $payment */
        $payment = new Payment();
        $this->assertNotNull($payment);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Payment $payment */
        $paymentModel = new Payment();

        $paymentData = factory(Payment::class)->make();
        foreach( $paymentData->toFillableArray() as $key => $value ) {
            $paymentModel->$key = $value;
        }
        $paymentModel->save();

        $this->assertNotNull(Payment::find($paymentModel->id));
    }

}

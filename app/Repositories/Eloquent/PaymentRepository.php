<?php namespace App\Repositories\Eloquent;

use \App\Repositories\PaymentRepositoryInterface;
use \App\Models\Payment;

class PaymentRepository extends SingleKeyModelRepository implements PaymentRepositoryInterface
{

    public function getBlankModel()
    {
        return new Payment();
    }

    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
        ];
    }

}

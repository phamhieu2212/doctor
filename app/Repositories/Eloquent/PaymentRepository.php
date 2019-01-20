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
    public function sumAllWithFilter($startDate,$endDate)
    {
        if($startDate == null and $endDate == null)
        {
            return Payment::where('status',1)->sum('price');
        }
        else
        {
            $startDate = date('Y-m-d 00:00:00',strtotime($startDate));
            $endDate = date('Y-m-d 23:59:59',strtotime($endDate));
            return Payment::where('status',1)
                ->where('created_at','>=',$startDate)
                ->where('created_at','<=',$endDate)->sum('price');
        }
    }

}

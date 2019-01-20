<?php namespace App\Repositories\Eloquent;

use \App\Repositories\AdminStatisticRepositoryInterface;
use \App\Models\AdminStatistic;

class AdminStatisticRepository extends SingleKeyModelRepository implements AdminStatisticRepositoryInterface
{

    public function getBlankModel()
    {
        return new AdminStatistic();
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
            return AdminStatistic::sum('total');
        }
        else
        {
            $startDate = date('Y-m-d 00:00:00',strtotime($startDate));
            $endDate = date('Y-m-d 23:59:59',strtotime($endDate));
            return AdminStatistic::where('created_at','>=',$startDate)
                ->where('created_at','<=',$endDate)->sum('total');
        }
    }

}

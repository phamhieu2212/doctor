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

}

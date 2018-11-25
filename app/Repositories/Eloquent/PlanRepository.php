<?php namespace App\Repositories\Eloquent;

use \App\Repositories\PlanRepositoryInterface;
use \App\Models\Plan;

class PlanRepository extends SingleKeyModelRepository implements PlanRepositoryInterface
{

    public function getBlankModel()
    {
        return new Plan();
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

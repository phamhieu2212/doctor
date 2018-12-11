<?php namespace App\Repositories\Eloquent;

use \App\Repositories\PlanRepositoryInterface;
use \App\Models\Plan;
use Illuminate\Support\Carbon;

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

    public function getOrderByDoctor($idDoctor, $order = 'id', $direction = 'asc', $offset = 0, $limit = 20)
    {
        $model = $this->getModelClassName();
        $now = Carbon::now();

        return $model::where('admin_user_id',$idDoctor)->where('started_at','>=',$now)->where('status',0)
            ->orderBy($order, $direction)->skip($offset)->take($limit)->get();


    }

    public function countOrderByDoctor($idDoctor)
    {
        $model = $this->getModelClassName();
        $now = Carbon::now();
        return $model::where('admin_user_id',$idDoctor)->where('started_at','>=',$now)->where('status',0)->count();
    }

}

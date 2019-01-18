<?php namespace App\Repositories\Eloquent;

use \App\Repositories\ClinicRepositoryInterface;
use \App\Models\Clinic;

class ClinicRepository extends SingleKeyModelRepository implements ClinicRepositoryInterface
{

    public function getBlankModel()
    {
        return new Clinic();
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

    public function getByFilterWithAdminUser($adminUser,$filter, $order = 'id', $direction = 'asc', $offset = 0, $limit = 20)
    {
        $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);
        $query = $this->buildOrder($query, $filter, $order, $direction);
        if($adminUser->roles[0]->role == 'super_user')
        {
            return $query->where('status','!=',3)->skip($offset)->take($limit)->get();
        }
        else
        {
            return $query->where('status','!=',3)->where('admin_user_id',$adminUser->id)->skip($offset)->take($limit)->get();
        }

    }

    public function countByFilterWithAdminUser($adminUser,$filter)
    {
        $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);
        if($adminUser->roles[0]->role == 'super_user')
        {
            return $query->where('status','!=',3)->count();
        }
        else
        {
            return $query->where('status','!=',3)->where('admin_user_id',$adminUser->id)->count();
        }

    }

}

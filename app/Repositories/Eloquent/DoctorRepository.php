<?php namespace App\Repositories\Eloquent;

use App\Models\DoctorSpecialty;
use App\Models\Plan;
use \App\Repositories\DoctorRepositoryInterface;
use \App\Models\Doctor;

class DoctorRepository extends SingleKeyModelRepository implements DoctorRepositoryInterface
{
    protected $querySearchTargets = ['name'];

    public function getBlankModel()
    {
        return new Doctor();
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

    public function getByFilter($filter, $order = 'id', $direction = 'asc', $offset = 0, $limit = 20)
    {
        if(isset($filter['specialty_id']) and isset($filter['day_start']))
        {
            $idAdminUserSpecialty = DoctorSpecialty::where('specialty_id',$filter['specialty_id'])
                ->pluck('admin_user_id')->toArray();

            $idAdminUser = Plan::where('started_at','>=',$filter['day_start'])->whereIn('admin_user_id',$idAdminUserSpecialty)
                ->where('started_at','<=',$filter['day_end'])->pluck('admin_user_id')->toArray();
            unset($filter['specialty_id']);
            unset($filter['day_end']);
            unset($filter['day_start']);
            $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);
            $query = $this->buildOrder($query, $filter, $order, $direction);
            return $query->whereIn('admin_user_id',$idAdminUser)->skip($offset)->take($limit)->get();


        }
        elseif(isset($filter['specialty_id']) or isset($filter['day_start']))
        {
            if(isset($filter['specialty_id']))
            {
                $idAdminUser = DoctorSpecialty::where('specialty_id',$filter['specialty_id'])
                    ->pluck('admin_user_id')->toArray();

            }
            else
            {
                $idAdminUser = Plan::where('started_at','>=',$filter['day_start'])
                    ->where('started_at','<=',$filter['day_end'])->pluck('admin_user_id')->toArray();
            }
            unset($filter['specialty_id']);
            unset($filter['day_end']);
            unset($filter['day_start']);
            $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);
            $query = $this->buildOrder($query, $filter, $order, $direction);
            return $query->whereIn('admin_user_id',$idAdminUser)->skip($offset)->take($limit)->get();


        }
        else
        {
            $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);
            $query = $this->buildOrder($query, $filter, $order, $direction);
            return $query->skip($offset)->take($limit)->get();
        }


    }

    public function countByFilter($filter)
    {
        $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);

        return $query->count();
    }

}

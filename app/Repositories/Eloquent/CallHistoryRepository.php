<?php namespace App\Repositories\Eloquent;

use App\Models\AdminUser;
use App\Models\Doctor;
use App\Models\User;
use \App\Repositories\CallHistoryRepositoryInterface;
use \App\Models\CallHistory;

class CallHistoryRepository extends SingleKeyModelRepository implements CallHistoryRepositoryInterface
{

    public function getBlankModel()
    {
        return new CallHistory();
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

    public function getByFilterWithPatient($idPatient,$filter, $order = 'id', $direction = 'asc', $offset = 0, $limit = 20)
    {
        if(isset($filter['query']))
        {
            $idAdminUser = Doctor::where('name','like', '%' . $filter['query'] . '%')->pluck('admin_user_id');
            unset($filter['query']);

            $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);
            $query = $this->buildOrder($query, $filter, $order, $direction);
            return $query->whereIn('admin_user_id',$idAdminUser)->where('user_id',$idPatient)->skip($offset)->take($limit)->get();
        }
        else
        {
            $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);
            $query = $this->buildOrder($query, $filter, $order, $direction);
            return $query->where('user_id',$idPatient)->skip($offset)->take($limit)->get();
        }


    }

    public function getByFilterWithDoctor($idDoctor,$filter, $order = 'id', $direction = 'asc', $offset = 0, $limit = 20)
    {
        if(isset($filter['query']))
        {
            $idUser = User::where('name','like', '%' . $filter['query'] . '%')->pluck('id');
            unset($filter['query']);

            $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);
            $query = $this->buildOrder($query, $filter, $order, $direction);
            return $query->whereIn('user_id',$idUser)->where('admin_user_id',$idDoctor)->skip($offset)->take($limit)->get();
        }
        else
        {
            $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);
            $query = $this->buildOrder($query, $filter, $order, $direction);
            return $query->where('admin_user_id',$idDoctor)->skip($offset)->take($limit)->get();
        }


    }

    public function checkRead($doctorId)
    {
        return $this->getBlankModel()
                    ->where('admin_user_id', '=', $doctorId)
                    ->where('caller', '=', CallHistory::PATIENT)
                    ->where('is_read', '=', CallHistory::NOT_YET_READ)
                    ->where('type', '=', CallHistory::MISS)
                    ->exists();
    }

    public function updateIsRead()
    {
        $this->getBlankModel()->where('is_read', '=', CallHistory::NOT_YET_READ)->update(["is_read" => CallHistory::IS_READ]);
    }
    public function countAllWithFilter($startDate,$endDate)
    {
        if($startDate == null and $endDate == null)
        {
            return CallHistory::count();
        }
        else
        {
            $startDate = date('Y-m-d 00:00:00',strtotime($startDate));
            $endDate = date('Y-m-d 23:59:59',strtotime($endDate));
            return CallHistory::where('created_at','>=',$startDate)
                ->where('created_at','<=',$endDate)->count();
        }
    }

}

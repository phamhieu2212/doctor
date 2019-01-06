<?php namespace App\Repositories\Eloquent;

use App\Models\AdminUser;
use App\Models\Doctor;
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

}

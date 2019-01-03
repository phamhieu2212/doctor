<?php namespace App\Repositories\Eloquent;

use \App\Repositories\FilePatientRepositoryInterface;
use \App\Models\FilePatient;

class FilePatientRepository extends SingleKeyModelRepository implements FilePatientRepositoryInterface
{

    public function getBlankModel()
    {
        return new FilePatient();
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

    public function getByFilterWithPatient($patient,$filter, $order = 'id', $direction = 'asc', $offset = 0, $limit = 20)
    {
        $query = $this->buildQueryByFilter($this->getBlankModel(), $filter);
        $query = $this->buildOrder($query, $filter, $order, $direction);
        return $query->where('user_id',$patient->id)->skip($offset)->take($limit)->get();

    }

}

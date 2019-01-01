<?php namespace App\Repositories\Eloquent;

use \App\Repositories\PointPatientRepositoryInterface;
use \App\Models\PointPatient;

class PointPatientRepository extends SingleKeyModelRepository implements PointPatientRepositoryInterface
{

    public function getBlankModel()
    {
        return new PointPatient();
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

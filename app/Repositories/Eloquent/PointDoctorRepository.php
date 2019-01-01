<?php namespace App\Repositories\Eloquent;

use \App\Repositories\PointDoctorRepositoryInterface;
use \App\Models\PointDoctor;

class PointDoctorRepository extends SingleKeyModelRepository implements PointDoctorRepositoryInterface
{

    public function getBlankModel()
    {
        return new PointDoctor();
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

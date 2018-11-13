<?php namespace App\Repositories\Eloquent;

use \App\Repositories\DoctorRepositoryInterface;
use \App\Models\Doctor;

class DoctorRepository extends SingleKeyModelRepository implements DoctorRepositoryInterface
{

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

}

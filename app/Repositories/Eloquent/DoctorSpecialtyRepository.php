<?php namespace App\Repositories\Eloquent;

use \App\Repositories\DoctorSpecialtyRepositoryInterface;
use \App\Models\DoctorSpecialty;

class DoctorSpecialtyRepository extends SingleKeyModelRepository implements DoctorSpecialtyRepositoryInterface
{

    public function getBlankModel()
    {
        return new DoctorSpecialty();
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

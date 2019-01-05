<?php namespace App\Repositories\Eloquent;

use \App\Repositories\PatientRepositoryInterface;
use \App\Models\Patient;

class PatientRepository extends SingleKeyModelRepository implements PatientRepositoryInterface
{

    public function getBlankModel()
    {
        return new Patient();
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

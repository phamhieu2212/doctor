<?php namespace App\Repositories\Eloquent;

use \App\Repositories\HospitalRepositoryInterface;
use \App\Models\Hospital;

class HospitalRepository extends SingleKeyModelRepository implements HospitalRepositoryInterface
{

    public function getBlankModel()
    {
        return new Hospital();
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

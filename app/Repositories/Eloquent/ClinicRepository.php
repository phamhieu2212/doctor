<?php namespace App\Repositories\Eloquent;

use \App\Repositories\ClinicRepositoryInterface;
use \App\Models\Clinic;

class ClinicRepository extends SingleKeyModelRepository implements ClinicRepositoryInterface
{

    public function getBlankModel()
    {
        return new Clinic();
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

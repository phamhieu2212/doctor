<?php namespace App\Repositories\Eloquent;

use \App\Repositories\SpecialtyRepositoryInterface;
use \App\Models\Specialty;

class SpecialtyRepository extends SingleKeyModelRepository implements SpecialtyRepositoryInterface
{

    public function getBlankModel()
    {
        return new Specialty();
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

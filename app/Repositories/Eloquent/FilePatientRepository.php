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

}

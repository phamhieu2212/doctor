<?php namespace App\Repositories\Eloquent;

use \App\Repositories\FilePatientImageRepositoryInterface;
use \App\Models\FilePatientImage;

class FilePatientImageRepository extends SingleKeyModelRepository implements FilePatientImageRepositoryInterface
{

    public function getBlankModel()
    {
        return new FilePatientImage();
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

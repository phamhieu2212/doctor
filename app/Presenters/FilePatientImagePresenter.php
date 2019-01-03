<?php

namespace App\Presenters;

use Illuminate\Support\Facades\Redis;
use App\Models\FilePatient;
use App\Models\Image;

class FilePatientImagePresenter extends BasePresenter
{
    protected $multilingualFields = [];

    protected $imageFields = [];

    /**
    * @return \App\Models\FilePatient
    * */
    public function filePatient()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('FilePatientModel');
            $cached = Redis::hget($cacheKey, $this->entity->file_patient_id);

            if( $cached ) {
                $filePatient = new FilePatient(json_decode($cached, true));
                $filePatient['id'] = json_decode($cached, true)['id'];
                return $filePatient;
            } else {
                $filePatient = $this->entity->filePatient;
                Redis::hsetnx($cacheKey, $this->entity->file_patient_id, $filePatient);
                return $filePatient;
            }
        }

        $filePatient = $this->entity->filePatient;
        return $filePatient;
    }

    /**
    * @return \App\Models\Image
    * */
    public function image()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('ImageModel');
            $cached = Redis::hget($cacheKey, $this->entity->image_id);

            if( $cached ) {
                $image = new Image(json_decode($cached, true));
                $image['id'] = json_decode($cached, true)['id'];
                return $image;
            } else {
                $image = $this->entity->image;
                Redis::hsetnx($cacheKey, $this->entity->image_id, $image);
                return $image;
            }
        }

        $image = $this->entity->image;
        return $image;
    }

    
}

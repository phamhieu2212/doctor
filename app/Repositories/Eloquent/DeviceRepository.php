<?php namespace App\Repositories\Eloquent;

use \App\Repositories\DeviceRepositoryInterface;
use \App\Models\Device;

class DeviceRepository extends SingleKeyModelRepository implements DeviceRepositoryInterface
{

    public function getBlankModel()
    {
        return new Device();
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

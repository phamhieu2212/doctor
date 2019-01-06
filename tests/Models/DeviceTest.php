<?php namespace Tests\Models;

use App\Models\Device;
use Tests\TestCase;

class DeviceTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Device $device */
        $device = new Device();
        $this->assertNotNull($device);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Device $device */
        $deviceModel = new Device();

        $deviceData = factory(Device::class)->make();
        foreach( $deviceData->toFillableArray() as $key => $value ) {
            $deviceModel->$key = $value;
        }
        $deviceModel->save();

        $this->assertNotNull(Device::find($deviceModel->id));
    }

}

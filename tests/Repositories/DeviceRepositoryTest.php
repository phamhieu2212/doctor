<?php namespace Tests\Repositories;

use App\Models\Device;
use Tests\TestCase;

class DeviceRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\DeviceRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DeviceRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $devices = factory(Device::class, 3)->create();
        $deviceIds = $devices->pluck('id')->toArray();

        /** @var  \App\Repositories\DeviceRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DeviceRepositoryInterface::class);
        $this->assertNotNull($repository);

        $devicesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Device::class, $devicesCheck[0]);

        $devicesCheck = $repository->getByIds($deviceIds);
        $this->assertEquals(3, count($devicesCheck));
    }

    public function testFind()
    {
        $devices = factory(Device::class, 3)->create();
        $deviceIds = $devices->pluck('id')->toArray();

        /** @var  \App\Repositories\DeviceRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DeviceRepositoryInterface::class);
        $this->assertNotNull($repository);

        $deviceCheck = $repository->find($deviceIds[0]);
        $this->assertEquals($deviceIds[0], $deviceCheck->id);
    }

    public function testCreate()
    {
        $deviceData = factory(Device::class)->make();

        /** @var  \App\Repositories\DeviceRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DeviceRepositoryInterface::class);
        $this->assertNotNull($repository);

        $deviceCheck = $repository->create($deviceData->toFillableArray());
        $this->assertNotNull($deviceCheck);
    }

    public function testUpdate()
    {
        $deviceData = factory(Device::class)->create();

        /** @var  \App\Repositories\DeviceRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DeviceRepositoryInterface::class);
        $this->assertNotNull($repository);

        $deviceCheck = $repository->update($deviceData, $deviceData->toFillableArray());
        $this->assertNotNull($deviceCheck);
    }

    public function testDelete()
    {
        $deviceData = factory(Device::class)->create();

        /** @var  \App\Repositories\DeviceRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DeviceRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($deviceData);

        $deviceCheck = $repository->find($deviceData->id);
        $this->assertNull($deviceCheck);
    }

}

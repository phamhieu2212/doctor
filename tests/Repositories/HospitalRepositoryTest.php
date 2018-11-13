<?php namespace Tests\Repositories;

use App\Models\Hospital;
use Tests\TestCase;

class HospitalRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\HospitalRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\HospitalRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $hospitals = factory(Hospital::class, 3)->create();
        $hospitalIds = $hospitals->pluck('id')->toArray();

        /** @var  \App\Repositories\HospitalRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\HospitalRepositoryInterface::class);
        $this->assertNotNull($repository);

        $hospitalsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Hospital::class, $hospitalsCheck[0]);

        $hospitalsCheck = $repository->getByIds($hospitalIds);
        $this->assertEquals(3, count($hospitalsCheck));
    }

    public function testFind()
    {
        $hospitals = factory(Hospital::class, 3)->create();
        $hospitalIds = $hospitals->pluck('id')->toArray();

        /** @var  \App\Repositories\HospitalRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\HospitalRepositoryInterface::class);
        $this->assertNotNull($repository);

        $hospitalCheck = $repository->find($hospitalIds[0]);
        $this->assertEquals($hospitalIds[0], $hospitalCheck->id);
    }

    public function testCreate()
    {
        $hospitalData = factory(Hospital::class)->make();

        /** @var  \App\Repositories\HospitalRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\HospitalRepositoryInterface::class);
        $this->assertNotNull($repository);

        $hospitalCheck = $repository->create($hospitalData->toFillableArray());
        $this->assertNotNull($hospitalCheck);
    }

    public function testUpdate()
    {
        $hospitalData = factory(Hospital::class)->create();

        /** @var  \App\Repositories\HospitalRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\HospitalRepositoryInterface::class);
        $this->assertNotNull($repository);

        $hospitalCheck = $repository->update($hospitalData, $hospitalData->toFillableArray());
        $this->assertNotNull($hospitalCheck);
    }

    public function testDelete()
    {
        $hospitalData = factory(Hospital::class)->create();

        /** @var  \App\Repositories\HospitalRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\HospitalRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($hospitalData);

        $hospitalCheck = $repository->find($hospitalData->id);
        $this->assertNull($hospitalCheck);
    }

}

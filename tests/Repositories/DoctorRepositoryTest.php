<?php namespace Tests\Repositories;

use App\Models\Doctor;
use Tests\TestCase;

class DoctorRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\DoctorRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DoctorRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $doctors = factory(Doctor::class, 3)->create();
        $doctorIds = $doctors->pluck('id')->toArray();

        /** @var  \App\Repositories\DoctorRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DoctorRepositoryInterface::class);
        $this->assertNotNull($repository);

        $doctorsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Doctor::class, $doctorsCheck[0]);

        $doctorsCheck = $repository->getByIds($doctorIds);
        $this->assertEquals(3, count($doctorsCheck));
    }

    public function testFind()
    {
        $doctors = factory(Doctor::class, 3)->create();
        $doctorIds = $doctors->pluck('id')->toArray();

        /** @var  \App\Repositories\DoctorRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DoctorRepositoryInterface::class);
        $this->assertNotNull($repository);

        $doctorCheck = $repository->find($doctorIds[0]);
        $this->assertEquals($doctorIds[0], $doctorCheck->id);
    }

    public function testCreate()
    {
        $doctorData = factory(Doctor::class)->make();

        /** @var  \App\Repositories\DoctorRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DoctorRepositoryInterface::class);
        $this->assertNotNull($repository);

        $doctorCheck = $repository->create($doctorData->toFillableArray());
        $this->assertNotNull($doctorCheck);
    }

    public function testUpdate()
    {
        $doctorData = factory(Doctor::class)->create();

        /** @var  \App\Repositories\DoctorRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DoctorRepositoryInterface::class);
        $this->assertNotNull($repository);

        $doctorCheck = $repository->update($doctorData, $doctorData->toFillableArray());
        $this->assertNotNull($doctorCheck);
    }

    public function testDelete()
    {
        $doctorData = factory(Doctor::class)->create();

        /** @var  \App\Repositories\DoctorRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DoctorRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($doctorData);

        $doctorCheck = $repository->find($doctorData->id);
        $this->assertNull($doctorCheck);
    }

}

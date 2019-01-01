<?php namespace Tests\Repositories;

use App\Models\PointDoctor;
use Tests\TestCase;

class PointDoctorRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\PointDoctorRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PointDoctorRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $pointDoctors = factory(PointDoctor::class, 3)->create();
        $pointDoctorIds = $pointDoctors->pluck('id')->toArray();

        /** @var  \App\Repositories\PointDoctorRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PointDoctorRepositoryInterface::class);
        $this->assertNotNull($repository);

        $pointDoctorsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(PointDoctor::class, $pointDoctorsCheck[0]);

        $pointDoctorsCheck = $repository->getByIds($pointDoctorIds);
        $this->assertEquals(3, count($pointDoctorsCheck));
    }

    public function testFind()
    {
        $pointDoctors = factory(PointDoctor::class, 3)->create();
        $pointDoctorIds = $pointDoctors->pluck('id')->toArray();

        /** @var  \App\Repositories\PointDoctorRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PointDoctorRepositoryInterface::class);
        $this->assertNotNull($repository);

        $pointDoctorCheck = $repository->find($pointDoctorIds[0]);
        $this->assertEquals($pointDoctorIds[0], $pointDoctorCheck->id);
    }

    public function testCreate()
    {
        $pointDoctorData = factory(PointDoctor::class)->make();

        /** @var  \App\Repositories\PointDoctorRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PointDoctorRepositoryInterface::class);
        $this->assertNotNull($repository);

        $pointDoctorCheck = $repository->create($pointDoctorData->toFillableArray());
        $this->assertNotNull($pointDoctorCheck);
    }

    public function testUpdate()
    {
        $pointDoctorData = factory(PointDoctor::class)->create();

        /** @var  \App\Repositories\PointDoctorRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PointDoctorRepositoryInterface::class);
        $this->assertNotNull($repository);

        $pointDoctorCheck = $repository->update($pointDoctorData, $pointDoctorData->toFillableArray());
        $this->assertNotNull($pointDoctorCheck);
    }

    public function testDelete()
    {
        $pointDoctorData = factory(PointDoctor::class)->create();

        /** @var  \App\Repositories\PointDoctorRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PointDoctorRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($pointDoctorData);

        $pointDoctorCheck = $repository->find($pointDoctorData->id);
        $this->assertNull($pointDoctorCheck);
    }

}

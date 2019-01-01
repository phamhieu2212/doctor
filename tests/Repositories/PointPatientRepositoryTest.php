<?php namespace Tests\Repositories;

use App\Models\PointPatient;
use Tests\TestCase;

class PointPatientRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\PointPatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PointPatientRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $pointPatients = factory(PointPatient::class, 3)->create();
        $pointPatientIds = $pointPatients->pluck('id')->toArray();

        /** @var  \App\Repositories\PointPatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PointPatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $pointPatientsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(PointPatient::class, $pointPatientsCheck[0]);

        $pointPatientsCheck = $repository->getByIds($pointPatientIds);
        $this->assertEquals(3, count($pointPatientsCheck));
    }

    public function testFind()
    {
        $pointPatients = factory(PointPatient::class, 3)->create();
        $pointPatientIds = $pointPatients->pluck('id')->toArray();

        /** @var  \App\Repositories\PointPatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PointPatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $pointPatientCheck = $repository->find($pointPatientIds[0]);
        $this->assertEquals($pointPatientIds[0], $pointPatientCheck->id);
    }

    public function testCreate()
    {
        $pointPatientData = factory(PointPatient::class)->make();

        /** @var  \App\Repositories\PointPatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PointPatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $pointPatientCheck = $repository->create($pointPatientData->toFillableArray());
        $this->assertNotNull($pointPatientCheck);
    }

    public function testUpdate()
    {
        $pointPatientData = factory(PointPatient::class)->create();

        /** @var  \App\Repositories\PointPatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PointPatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $pointPatientCheck = $repository->update($pointPatientData, $pointPatientData->toFillableArray());
        $this->assertNotNull($pointPatientCheck);
    }

    public function testDelete()
    {
        $pointPatientData = factory(PointPatient::class)->create();

        /** @var  \App\Repositories\PointPatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PointPatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($pointPatientData);

        $pointPatientCheck = $repository->find($pointPatientData->id);
        $this->assertNull($pointPatientCheck);
    }

}

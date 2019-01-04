<?php namespace Tests\Repositories;

use App\Models\Patient;
use Tests\TestCase;

class PatientRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\PatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PatientRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $patients = factory(Patient::class, 3)->create();
        $patientIds = $patients->pluck('id')->toArray();

        /** @var  \App\Repositories\PatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $patientsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Patient::class, $patientsCheck[0]);

        $patientsCheck = $repository->getByIds($patientIds);
        $this->assertEquals(3, count($patientsCheck));
    }

    public function testFind()
    {
        $patients = factory(Patient::class, 3)->create();
        $patientIds = $patients->pluck('id')->toArray();

        /** @var  \App\Repositories\PatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $patientCheck = $repository->find($patientIds[0]);
        $this->assertEquals($patientIds[0], $patientCheck->id);
    }

    public function testCreate()
    {
        $patientData = factory(Patient::class)->make();

        /** @var  \App\Repositories\PatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $patientCheck = $repository->create($patientData->toFillableArray());
        $this->assertNotNull($patientCheck);
    }

    public function testUpdate()
    {
        $patientData = factory(Patient::class)->create();

        /** @var  \App\Repositories\PatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $patientCheck = $repository->update($patientData, $patientData->toFillableArray());
        $this->assertNotNull($patientCheck);
    }

    public function testDelete()
    {
        $patientData = factory(Patient::class)->create();

        /** @var  \App\Repositories\PatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($patientData);

        $patientCheck = $repository->find($patientData->id);
        $this->assertNull($patientCheck);
    }

}

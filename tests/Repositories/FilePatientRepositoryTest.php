<?php namespace Tests\Repositories;

use App\Models\FilePatient;
use Tests\TestCase;

class FilePatientRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\FilePatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FilePatientRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $filePatients = factory(FilePatient::class, 3)->create();
        $filePatientIds = $filePatients->pluck('id')->toArray();

        /** @var  \App\Repositories\FilePatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FilePatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $filePatientsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(FilePatient::class, $filePatientsCheck[0]);

        $filePatientsCheck = $repository->getByIds($filePatientIds);
        $this->assertEquals(3, count($filePatientsCheck));
    }

    public function testFind()
    {
        $filePatients = factory(FilePatient::class, 3)->create();
        $filePatientIds = $filePatients->pluck('id')->toArray();

        /** @var  \App\Repositories\FilePatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FilePatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $filePatientCheck = $repository->find($filePatientIds[0]);
        $this->assertEquals($filePatientIds[0], $filePatientCheck->id);
    }

    public function testCreate()
    {
        $filePatientData = factory(FilePatient::class)->make();

        /** @var  \App\Repositories\FilePatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FilePatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $filePatientCheck = $repository->create($filePatientData->toFillableArray());
        $this->assertNotNull($filePatientCheck);
    }

    public function testUpdate()
    {
        $filePatientData = factory(FilePatient::class)->create();

        /** @var  \App\Repositories\FilePatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FilePatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $filePatientCheck = $repository->update($filePatientData, $filePatientData->toFillableArray());
        $this->assertNotNull($filePatientCheck);
    }

    public function testDelete()
    {
        $filePatientData = factory(FilePatient::class)->create();

        /** @var  \App\Repositories\FilePatientRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FilePatientRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($filePatientData);

        $filePatientCheck = $repository->find($filePatientData->id);
        $this->assertNull($filePatientCheck);
    }

}

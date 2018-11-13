<?php namespace Tests\Repositories;

use App\Models\DoctorSpecialty;
use Tests\TestCase;

class DoctorSpecialtyRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\DoctorSpecialtyRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DoctorSpecialtyRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $doctorSpecialties = factory(DoctorSpecialty::class, 3)->create();
        $doctorSpecialtyIds = $doctorSpecialties->pluck('id')->toArray();

        /** @var  \App\Repositories\DoctorSpecialtyRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DoctorSpecialtyRepositoryInterface::class);
        $this->assertNotNull($repository);

        $doctorSpecialtiesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(DoctorSpecialty::class, $doctorSpecialtiesCheck[0]);

        $doctorSpecialtiesCheck = $repository->getByIds($doctorSpecialtyIds);
        $this->assertEquals(3, count($doctorSpecialtiesCheck));
    }

    public function testFind()
    {
        $doctorSpecialties = factory(DoctorSpecialty::class, 3)->create();
        $doctorSpecialtyIds = $doctorSpecialties->pluck('id')->toArray();

        /** @var  \App\Repositories\DoctorSpecialtyRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DoctorSpecialtyRepositoryInterface::class);
        $this->assertNotNull($repository);

        $doctorSpecialtyCheck = $repository->find($doctorSpecialtyIds[0]);
        $this->assertEquals($doctorSpecialtyIds[0], $doctorSpecialtyCheck->id);
    }

    public function testCreate()
    {
        $doctorSpecialtyData = factory(DoctorSpecialty::class)->make();

        /** @var  \App\Repositories\DoctorSpecialtyRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DoctorSpecialtyRepositoryInterface::class);
        $this->assertNotNull($repository);

        $doctorSpecialtyCheck = $repository->create($doctorSpecialtyData->toFillableArray());
        $this->assertNotNull($doctorSpecialtyCheck);
    }

    public function testUpdate()
    {
        $doctorSpecialtyData = factory(DoctorSpecialty::class)->create();

        /** @var  \App\Repositories\DoctorSpecialtyRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DoctorSpecialtyRepositoryInterface::class);
        $this->assertNotNull($repository);

        $doctorSpecialtyCheck = $repository->update($doctorSpecialtyData, $doctorSpecialtyData->toFillableArray());
        $this->assertNotNull($doctorSpecialtyCheck);
    }

    public function testDelete()
    {
        $doctorSpecialtyData = factory(DoctorSpecialty::class)->create();

        /** @var  \App\Repositories\DoctorSpecialtyRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\DoctorSpecialtyRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($doctorSpecialtyData);

        $doctorSpecialtyCheck = $repository->find($doctorSpecialtyData->id);
        $this->assertNull($doctorSpecialtyCheck);
    }

}

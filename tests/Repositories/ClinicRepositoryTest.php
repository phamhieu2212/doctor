<?php namespace Tests\Repositories;

use App\Models\Clinic;
use Tests\TestCase;

class ClinicRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\ClinicRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\ClinicRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $clinics = factory(Clinic::class, 3)->create();
        $clinicIds = $clinics->pluck('id')->toArray();

        /** @var  \App\Repositories\ClinicRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\ClinicRepositoryInterface::class);
        $this->assertNotNull($repository);

        $clinicsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Clinic::class, $clinicsCheck[0]);

        $clinicsCheck = $repository->getByIds($clinicIds);
        $this->assertEquals(3, count($clinicsCheck));
    }

    public function testFind()
    {
        $clinics = factory(Clinic::class, 3)->create();
        $clinicIds = $clinics->pluck('id')->toArray();

        /** @var  \App\Repositories\ClinicRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\ClinicRepositoryInterface::class);
        $this->assertNotNull($repository);

        $clinicCheck = $repository->find($clinicIds[0]);
        $this->assertEquals($clinicIds[0], $clinicCheck->id);
    }

    public function testCreate()
    {
        $clinicData = factory(Clinic::class)->make();

        /** @var  \App\Repositories\ClinicRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\ClinicRepositoryInterface::class);
        $this->assertNotNull($repository);

        $clinicCheck = $repository->create($clinicData->toFillableArray());
        $this->assertNotNull($clinicCheck);
    }

    public function testUpdate()
    {
        $clinicData = factory(Clinic::class)->create();

        /** @var  \App\Repositories\ClinicRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\ClinicRepositoryInterface::class);
        $this->assertNotNull($repository);

        $clinicCheck = $repository->update($clinicData, $clinicData->toFillableArray());
        $this->assertNotNull($clinicCheck);
    }

    public function testDelete()
    {
        $clinicData = factory(Clinic::class)->create();

        /** @var  \App\Repositories\ClinicRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\ClinicRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($clinicData);

        $clinicCheck = $repository->find($clinicData->id);
        $this->assertNull($clinicCheck);
    }

}

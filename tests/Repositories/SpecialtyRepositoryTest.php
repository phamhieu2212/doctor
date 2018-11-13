<?php namespace Tests\Repositories;

use App\Models\Specialty;
use Tests\TestCase;

class SpecialtyRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\SpecialtyRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\SpecialtyRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $specialties = factory(Specialty::class, 3)->create();
        $specialtyIds = $specialties->pluck('id')->toArray();

        /** @var  \App\Repositories\SpecialtyRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\SpecialtyRepositoryInterface::class);
        $this->assertNotNull($repository);

        $specialtiesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Specialty::class, $specialtiesCheck[0]);

        $specialtiesCheck = $repository->getByIds($specialtyIds);
        $this->assertEquals(3, count($specialtiesCheck));
    }

    public function testFind()
    {
        $specialties = factory(Specialty::class, 3)->create();
        $specialtyIds = $specialties->pluck('id')->toArray();

        /** @var  \App\Repositories\SpecialtyRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\SpecialtyRepositoryInterface::class);
        $this->assertNotNull($repository);

        $specialtyCheck = $repository->find($specialtyIds[0]);
        $this->assertEquals($specialtyIds[0], $specialtyCheck->id);
    }

    public function testCreate()
    {
        $specialtyData = factory(Specialty::class)->make();

        /** @var  \App\Repositories\SpecialtyRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\SpecialtyRepositoryInterface::class);
        $this->assertNotNull($repository);

        $specialtyCheck = $repository->create($specialtyData->toFillableArray());
        $this->assertNotNull($specialtyCheck);
    }

    public function testUpdate()
    {
        $specialtyData = factory(Specialty::class)->create();

        /** @var  \App\Repositories\SpecialtyRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\SpecialtyRepositoryInterface::class);
        $this->assertNotNull($repository);

        $specialtyCheck = $repository->update($specialtyData, $specialtyData->toFillableArray());
        $this->assertNotNull($specialtyCheck);
    }

    public function testDelete()
    {
        $specialtyData = factory(Specialty::class)->create();

        /** @var  \App\Repositories\SpecialtyRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\SpecialtyRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($specialtyData);

        $specialtyCheck = $repository->find($specialtyData->id);
        $this->assertNull($specialtyCheck);
    }

}

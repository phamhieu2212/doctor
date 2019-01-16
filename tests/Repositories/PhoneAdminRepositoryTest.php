<?php namespace Tests\Repositories;

use App\Models\PhoneAdmin;
use Tests\TestCase;

class PhoneAdminRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\PhoneAdminRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PhoneAdminRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $phoneAdmins = factory(PhoneAdmin::class, 3)->create();
        $phoneAdminIds = $phoneAdmins->pluck('id')->toArray();

        /** @var  \App\Repositories\PhoneAdminRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PhoneAdminRepositoryInterface::class);
        $this->assertNotNull($repository);

        $phoneAdminsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(PhoneAdmin::class, $phoneAdminsCheck[0]);

        $phoneAdminsCheck = $repository->getByIds($phoneAdminIds);
        $this->assertEquals(3, count($phoneAdminsCheck));
    }

    public function testFind()
    {
        $phoneAdmins = factory(PhoneAdmin::class, 3)->create();
        $phoneAdminIds = $phoneAdmins->pluck('id')->toArray();

        /** @var  \App\Repositories\PhoneAdminRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PhoneAdminRepositoryInterface::class);
        $this->assertNotNull($repository);

        $phoneAdminCheck = $repository->find($phoneAdminIds[0]);
        $this->assertEquals($phoneAdminIds[0], $phoneAdminCheck->id);
    }

    public function testCreate()
    {
        $phoneAdminData = factory(PhoneAdmin::class)->make();

        /** @var  \App\Repositories\PhoneAdminRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PhoneAdminRepositoryInterface::class);
        $this->assertNotNull($repository);

        $phoneAdminCheck = $repository->create($phoneAdminData->toFillableArray());
        $this->assertNotNull($phoneAdminCheck);
    }

    public function testUpdate()
    {
        $phoneAdminData = factory(PhoneAdmin::class)->create();

        /** @var  \App\Repositories\PhoneAdminRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PhoneAdminRepositoryInterface::class);
        $this->assertNotNull($repository);

        $phoneAdminCheck = $repository->update($phoneAdminData, $phoneAdminData->toFillableArray());
        $this->assertNotNull($phoneAdminCheck);
    }

    public function testDelete()
    {
        $phoneAdminData = factory(PhoneAdmin::class)->create();

        /** @var  \App\Repositories\PhoneAdminRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PhoneAdminRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($phoneAdminData);

        $phoneAdminCheck = $repository->find($phoneAdminData->id);
        $this->assertNull($phoneAdminCheck);
    }

}

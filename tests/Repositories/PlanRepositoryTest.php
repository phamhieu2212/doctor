<?php namespace Tests\Repositories;

use App\Models\Plan;
use Tests\TestCase;

class PlanRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\PlanRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PlanRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $plans = factory(Plan::class, 3)->create();
        $planIds = $plans->pluck('id')->toArray();

        /** @var  \App\Repositories\PlanRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PlanRepositoryInterface::class);
        $this->assertNotNull($repository);

        $plansCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Plan::class, $plansCheck[0]);

        $plansCheck = $repository->getByIds($planIds);
        $this->assertEquals(3, count($plansCheck));
    }

    public function testFind()
    {
        $plans = factory(Plan::class, 3)->create();
        $planIds = $plans->pluck('id')->toArray();

        /** @var  \App\Repositories\PlanRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PlanRepositoryInterface::class);
        $this->assertNotNull($repository);

        $planCheck = $repository->find($planIds[0]);
        $this->assertEquals($planIds[0], $planCheck->id);
    }

    public function testCreate()
    {
        $planData = factory(Plan::class)->make();

        /** @var  \App\Repositories\PlanRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PlanRepositoryInterface::class);
        $this->assertNotNull($repository);

        $planCheck = $repository->create($planData->toFillableArray());
        $this->assertNotNull($planCheck);
    }

    public function testUpdate()
    {
        $planData = factory(Plan::class)->create();

        /** @var  \App\Repositories\PlanRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PlanRepositoryInterface::class);
        $this->assertNotNull($repository);

        $planCheck = $repository->update($planData, $planData->toFillableArray());
        $this->assertNotNull($planCheck);
    }

    public function testDelete()
    {
        $planData = factory(Plan::class)->create();

        /** @var  \App\Repositories\PlanRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\PlanRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($planData);

        $planCheck = $repository->find($planData->id);
        $this->assertNull($planCheck);
    }

}

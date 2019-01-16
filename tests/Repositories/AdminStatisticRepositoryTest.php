<?php namespace Tests\Repositories;

use App\Models\AdminStatistic;
use Tests\TestCase;

class AdminStatisticRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\AdminStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\AdminStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $adminStatistics = factory(AdminStatistic::class, 3)->create();
        $adminStatisticIds = $adminStatistics->pluck('id')->toArray();

        /** @var  \App\Repositories\AdminStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\AdminStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $adminStatisticsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(AdminStatistic::class, $adminStatisticsCheck[0]);

        $adminStatisticsCheck = $repository->getByIds($adminStatisticIds);
        $this->assertEquals(3, count($adminStatisticsCheck));
    }

    public function testFind()
    {
        $adminStatistics = factory(AdminStatistic::class, 3)->create();
        $adminStatisticIds = $adminStatistics->pluck('id')->toArray();

        /** @var  \App\Repositories\AdminStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\AdminStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $adminStatisticCheck = $repository->find($adminStatisticIds[0]);
        $this->assertEquals($adminStatisticIds[0], $adminStatisticCheck->id);
    }

    public function testCreate()
    {
        $adminStatisticData = factory(AdminStatistic::class)->make();

        /** @var  \App\Repositories\AdminStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\AdminStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $adminStatisticCheck = $repository->create($adminStatisticData->toFillableArray());
        $this->assertNotNull($adminStatisticCheck);
    }

    public function testUpdate()
    {
        $adminStatisticData = factory(AdminStatistic::class)->create();

        /** @var  \App\Repositories\AdminStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\AdminStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $adminStatisticCheck = $repository->update($adminStatisticData, $adminStatisticData->toFillableArray());
        $this->assertNotNull($adminStatisticCheck);
    }

    public function testDelete()
    {
        $adminStatisticData = factory(AdminStatistic::class)->create();

        /** @var  \App\Repositories\AdminStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\AdminStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($adminStatisticData);

        $adminStatisticCheck = $repository->find($adminStatisticData->id);
        $this->assertNull($adminStatisticCheck);
    }

}

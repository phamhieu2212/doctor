<?php namespace Tests\Repositories;

use App\Models\CallHistory;
use Tests\TestCase;

class CallHistoryRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\CallHistoryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\CallHistoryRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $callHistories = factory(CallHistory::class, 3)->create();
        $callHistoryIds = $callHistories->pluck('id')->toArray();

        /** @var  \App\Repositories\CallHistoryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\CallHistoryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $callHistoriesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(CallHistory::class, $callHistoriesCheck[0]);

        $callHistoriesCheck = $repository->getByIds($callHistoryIds);
        $this->assertEquals(3, count($callHistoriesCheck));
    }

    public function testFind()
    {
        $callHistories = factory(CallHistory::class, 3)->create();
        $callHistoryIds = $callHistories->pluck('id')->toArray();

        /** @var  \App\Repositories\CallHistoryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\CallHistoryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $callHistoryCheck = $repository->find($callHistoryIds[0]);
        $this->assertEquals($callHistoryIds[0], $callHistoryCheck->id);
    }

    public function testCreate()
    {
        $callHistoryData = factory(CallHistory::class)->make();

        /** @var  \App\Repositories\CallHistoryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\CallHistoryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $callHistoryCheck = $repository->create($callHistoryData->toFillableArray());
        $this->assertNotNull($callHistoryCheck);
    }

    public function testUpdate()
    {
        $callHistoryData = factory(CallHistory::class)->create();

        /** @var  \App\Repositories\CallHistoryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\CallHistoryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $callHistoryCheck = $repository->update($callHistoryData, $callHistoryData->toFillableArray());
        $this->assertNotNull($callHistoryCheck);
    }

    public function testDelete()
    {
        $callHistoryData = factory(CallHistory::class)->create();

        /** @var  \App\Repositories\CallHistoryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\CallHistoryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($callHistoryData);

        $callHistoryCheck = $repository->find($callHistoryData->id);
        $this->assertNull($callHistoryCheck);
    }

}

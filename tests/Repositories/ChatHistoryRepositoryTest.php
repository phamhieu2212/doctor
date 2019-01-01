<?php namespace Tests\Repositories;

use App\Models\ChatHistory;
use Tests\TestCase;

class ChatHistoryRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\ChatHistoryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\ChatHistoryRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $chatHistories = factory(ChatHistory::class, 3)->create();
        $chatHistoryIds = $chatHistories->pluck('id')->toArray();

        /** @var  \App\Repositories\ChatHistoryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\ChatHistoryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $chatHistoriesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(ChatHistory::class, $chatHistoriesCheck[0]);

        $chatHistoriesCheck = $repository->getByIds($chatHistoryIds);
        $this->assertEquals(3, count($chatHistoriesCheck));
    }

    public function testFind()
    {
        $chatHistories = factory(ChatHistory::class, 3)->create();
        $chatHistoryIds = $chatHistories->pluck('id')->toArray();

        /** @var  \App\Repositories\ChatHistoryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\ChatHistoryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $chatHistoryCheck = $repository->find($chatHistoryIds[0]);
        $this->assertEquals($chatHistoryIds[0], $chatHistoryCheck->id);
    }

    public function testCreate()
    {
        $chatHistoryData = factory(ChatHistory::class)->make();

        /** @var  \App\Repositories\ChatHistoryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\ChatHistoryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $chatHistoryCheck = $repository->create($chatHistoryData->toFillableArray());
        $this->assertNotNull($chatHistoryCheck);
    }

    public function testUpdate()
    {
        $chatHistoryData = factory(ChatHistory::class)->create();

        /** @var  \App\Repositories\ChatHistoryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\ChatHistoryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $chatHistoryCheck = $repository->update($chatHistoryData, $chatHistoryData->toFillableArray());
        $this->assertNotNull($chatHistoryCheck);
    }

    public function testDelete()
    {
        $chatHistoryData = factory(ChatHistory::class)->create();

        /** @var  \App\Repositories\ChatHistoryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\ChatHistoryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($chatHistoryData);

        $chatHistoryCheck = $repository->find($chatHistoryData->id);
        $this->assertNull($chatHistoryCheck);
    }

}

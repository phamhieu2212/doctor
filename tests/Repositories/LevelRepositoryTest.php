<?php namespace Tests\Repositories;

use App\Models\Level;
use Tests\TestCase;

class LevelRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\LevelRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\LevelRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $levels = factory(Level::class, 3)->create();
        $levelIds = $levels->pluck('id')->toArray();

        /** @var  \App\Repositories\LevelRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\LevelRepositoryInterface::class);
        $this->assertNotNull($repository);

        $levelsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Level::class, $levelsCheck[0]);

        $levelsCheck = $repository->getByIds($levelIds);
        $this->assertEquals(3, count($levelsCheck));
    }

    public function testFind()
    {
        $levels = factory(Level::class, 3)->create();
        $levelIds = $levels->pluck('id')->toArray();

        /** @var  \App\Repositories\LevelRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\LevelRepositoryInterface::class);
        $this->assertNotNull($repository);

        $levelCheck = $repository->find($levelIds[0]);
        $this->assertEquals($levelIds[0], $levelCheck->id);
    }

    public function testCreate()
    {
        $levelData = factory(Level::class)->make();

        /** @var  \App\Repositories\LevelRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\LevelRepositoryInterface::class);
        $this->assertNotNull($repository);

        $levelCheck = $repository->create($levelData->toFillableArray());
        $this->assertNotNull($levelCheck);
    }

    public function testUpdate()
    {
        $levelData = factory(Level::class)->create();

        /** @var  \App\Repositories\LevelRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\LevelRepositoryInterface::class);
        $this->assertNotNull($repository);

        $levelCheck = $repository->update($levelData, $levelData->toFillableArray());
        $this->assertNotNull($levelCheck);
    }

    public function testDelete()
    {
        $levelData = factory(Level::class)->create();

        /** @var  \App\Repositories\LevelRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\LevelRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($levelData);

        $levelCheck = $repository->find($levelData->id);
        $this->assertNull($levelCheck);
    }

}

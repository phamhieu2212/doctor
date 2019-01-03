<?php namespace Tests\Repositories;

use App\Models\FilePatientImage;
use Tests\TestCase;

class FilePatientImageRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\FilePatientImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FilePatientImageRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $filePatientImages = factory(FilePatientImage::class, 3)->create();
        $filePatientImageIds = $filePatientImages->pluck('id')->toArray();

        /** @var  \App\Repositories\FilePatientImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FilePatientImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $filePatientImagesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(FilePatientImage::class, $filePatientImagesCheck[0]);

        $filePatientImagesCheck = $repository->getByIds($filePatientImageIds);
        $this->assertEquals(3, count($filePatientImagesCheck));
    }

    public function testFind()
    {
        $filePatientImages = factory(FilePatientImage::class, 3)->create();
        $filePatientImageIds = $filePatientImages->pluck('id')->toArray();

        /** @var  \App\Repositories\FilePatientImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FilePatientImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $filePatientImageCheck = $repository->find($filePatientImageIds[0]);
        $this->assertEquals($filePatientImageIds[0], $filePatientImageCheck->id);
    }

    public function testCreate()
    {
        $filePatientImageData = factory(FilePatientImage::class)->make();

        /** @var  \App\Repositories\FilePatientImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FilePatientImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $filePatientImageCheck = $repository->create($filePatientImageData->toFillableArray());
        $this->assertNotNull($filePatientImageCheck);
    }

    public function testUpdate()
    {
        $filePatientImageData = factory(FilePatientImage::class)->create();

        /** @var  \App\Repositories\FilePatientImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FilePatientImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $filePatientImageCheck = $repository->update($filePatientImageData, $filePatientImageData->toFillableArray());
        $this->assertNotNull($filePatientImageCheck);
    }

    public function testDelete()
    {
        $filePatientImageData = factory(FilePatientImage::class)->create();

        /** @var  \App\Repositories\FilePatientImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FilePatientImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($filePatientImageData);

        $filePatientImageCheck = $repository->find($filePatientImageData->id);
        $this->assertNull($filePatientImageCheck);
    }

}

<?php namespace Tests\Models;

use App\Models\FilePatientImage;
use Tests\TestCase;

class FilePatientImageTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\FilePatientImage $filePatientImage */
        $filePatientImage = new FilePatientImage();
        $this->assertNotNull($filePatientImage);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\FilePatientImage $filePatientImage */
        $filePatientImageModel = new FilePatientImage();

        $filePatientImageData = factory(FilePatientImage::class)->make();
        foreach( $filePatientImageData->toFillableArray() as $key => $value ) {
            $filePatientImageModel->$key = $value;
        }
        $filePatientImageModel->save();

        $this->assertNotNull(FilePatientImage::find($filePatientImageModel->id));
    }

}

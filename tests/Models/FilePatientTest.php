<?php namespace Tests\Models;

use App\Models\FilePatient;
use Tests\TestCase;

class FilePatientTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\FilePatient $filePatient */
        $filePatient = new FilePatient();
        $this->assertNotNull($filePatient);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\FilePatient $filePatient */
        $filePatientModel = new FilePatient();

        $filePatientData = factory(FilePatient::class)->make();
        foreach( $filePatientData->toFillableArray() as $key => $value ) {
            $filePatientModel->$key = $value;
        }
        $filePatientModel->save();

        $this->assertNotNull(FilePatient::find($filePatientModel->id));
    }

}

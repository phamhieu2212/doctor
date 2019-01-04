<?php namespace Tests\Models;

use App\Models\Patient;
use Tests\TestCase;

class PatientTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Patient $patient */
        $patient = new Patient();
        $this->assertNotNull($patient);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Patient $patient */
        $patientModel = new Patient();

        $patientData = factory(Patient::class)->make();
        foreach( $patientData->toFillableArray() as $key => $value ) {
            $patientModel->$key = $value;
        }
        $patientModel->save();

        $this->assertNotNull(Patient::find($patientModel->id));
    }

}

<?php namespace Tests\Models;

use App\Models\PointPatient;
use Tests\TestCase;

class PointPatientTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\PointPatient $pointPatient */
        $pointPatient = new PointPatient();
        $this->assertNotNull($pointPatient);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\PointPatient $pointPatient */
        $pointPatientModel = new PointPatient();

        $pointPatientData = factory(PointPatient::class)->make();
        foreach( $pointPatientData->toFillableArray() as $key => $value ) {
            $pointPatientModel->$key = $value;
        }
        $pointPatientModel->save();

        $this->assertNotNull(PointPatient::find($pointPatientModel->id));
    }

}

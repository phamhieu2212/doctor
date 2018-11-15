<?php namespace Tests\Models;

use App\Models\Clinic;
use Tests\TestCase;

class ClinicTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Clinic $clinic */
        $clinic = new Clinic();
        $this->assertNotNull($clinic);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Clinic $clinic */
        $clinicModel = new Clinic();

        $clinicData = factory(Clinic::class)->make();
        foreach( $clinicData->toFillableArray() as $key => $value ) {
            $clinicModel->$key = $value;
        }
        $clinicModel->save();

        $this->assertNotNull(Clinic::find($clinicModel->id));
    }

}

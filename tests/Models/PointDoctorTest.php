<?php namespace Tests\Models;

use App\Models\PointDoctor;
use Tests\TestCase;

class PointDoctorTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\PointDoctor $pointDoctor */
        $pointDoctor = new PointDoctor();
        $this->assertNotNull($pointDoctor);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\PointDoctor $pointDoctor */
        $pointDoctorModel = new PointDoctor();

        $pointDoctorData = factory(PointDoctor::class)->make();
        foreach( $pointDoctorData->toFillableArray() as $key => $value ) {
            $pointDoctorModel->$key = $value;
        }
        $pointDoctorModel->save();

        $this->assertNotNull(PointDoctor::find($pointDoctorModel->id));
    }

}

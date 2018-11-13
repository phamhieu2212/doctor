<?php namespace Tests\Models;

use App\Models\Doctor;
use Tests\TestCase;

class DoctorTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Doctor $doctor */
        $doctor = new Doctor();
        $this->assertNotNull($doctor);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Doctor $doctor */
        $doctorModel = new Doctor();

        $doctorData = factory(Doctor::class)->make();
        foreach( $doctorData->toFillableArray() as $key => $value ) {
            $doctorModel->$key = $value;
        }
        $doctorModel->save();

        $this->assertNotNull(Doctor::find($doctorModel->id));
    }

}

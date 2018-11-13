<?php namespace Tests\Models;

use App\Models\DoctorSpecialty;
use Tests\TestCase;

class DoctorSpecialtyTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\DoctorSpecialty $doctorSpecialty */
        $doctorSpecialty = new DoctorSpecialty();
        $this->assertNotNull($doctorSpecialty);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\DoctorSpecialty $doctorSpecialty */
        $doctorSpecialtyModel = new DoctorSpecialty();

        $doctorSpecialtyData = factory(DoctorSpecialty::class)->make();
        foreach( $doctorSpecialtyData->toFillableArray() as $key => $value ) {
            $doctorSpecialtyModel->$key = $value;
        }
        $doctorSpecialtyModel->save();

        $this->assertNotNull(DoctorSpecialty::find($doctorSpecialtyModel->id));
    }

}

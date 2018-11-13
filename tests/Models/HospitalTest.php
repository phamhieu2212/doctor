<?php namespace Tests\Models;

use App\Models\Hospital;
use Tests\TestCase;

class HospitalTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Hospital $hospital */
        $hospital = new Hospital();
        $this->assertNotNull($hospital);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Hospital $hospital */
        $hospitalModel = new Hospital();

        $hospitalData = factory(Hospital::class)->make();
        foreach( $hospitalData->toFillableArray() as $key => $value ) {
            $hospitalModel->$key = $value;
        }
        $hospitalModel->save();

        $this->assertNotNull(Hospital::find($hospitalModel->id));
    }

}

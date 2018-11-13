<?php namespace Tests\Models;

use App\Models\Specialty;
use Tests\TestCase;

class SpecialtyTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Specialty $specialty */
        $specialty = new Specialty();
        $this->assertNotNull($specialty);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Specialty $specialty */
        $specialtyModel = new Specialty();

        $specialtyData = factory(Specialty::class)->make();
        foreach( $specialtyData->toFillableArray() as $key => $value ) {
            $specialtyModel->$key = $value;
        }
        $specialtyModel->save();

        $this->assertNotNull(Specialty::find($specialtyModel->id));
    }

}

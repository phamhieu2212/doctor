<?php namespace Tests\Models;

use App\Models\PhoneAdmin;
use Tests\TestCase;

class PhoneAdminTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\PhoneAdmin $phoneAdmin */
        $phoneAdmin = new PhoneAdmin();
        $this->assertNotNull($phoneAdmin);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\PhoneAdmin $phoneAdmin */
        $phoneAdminModel = new PhoneAdmin();

        $phoneAdminData = factory(PhoneAdmin::class)->make();
        foreach( $phoneAdminData->toFillableArray() as $key => $value ) {
            $phoneAdminModel->$key = $value;
        }
        $phoneAdminModel->save();

        $this->assertNotNull(PhoneAdmin::find($phoneAdminModel->id));
    }

}

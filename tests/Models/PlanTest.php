<?php namespace Tests\Models;

use App\Models\Plan;
use Tests\TestCase;

class PlanTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Plan $plan */
        $plan = new Plan();
        $this->assertNotNull($plan);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Plan $plan */
        $planModel = new Plan();

        $planData = factory(Plan::class)->make();
        foreach( $planData->toFillableArray() as $key => $value ) {
            $planModel->$key = $value;
        }
        $planModel->save();

        $this->assertNotNull(Plan::find($planModel->id));
    }

}

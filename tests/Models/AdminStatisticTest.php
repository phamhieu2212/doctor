<?php namespace Tests\Models;

use App\Models\AdminStatistic;
use Tests\TestCase;

class AdminStatisticTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\AdminStatistic $adminStatistic */
        $adminStatistic = new AdminStatistic();
        $this->assertNotNull($adminStatistic);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\AdminStatistic $adminStatistic */
        $adminStatisticModel = new AdminStatistic();

        $adminStatisticData = factory(AdminStatistic::class)->make();
        foreach( $adminStatisticData->toFillableArray() as $key => $value ) {
            $adminStatisticModel->$key = $value;
        }
        $adminStatisticModel->save();

        $this->assertNotNull(AdminStatistic::find($adminStatisticModel->id));
    }

}

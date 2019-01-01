<?php namespace Tests\Models;

use App\Models\CallHistory;
use Tests\TestCase;

class CallHistoryTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\CallHistory $callHistory */
        $callHistory = new CallHistory();
        $this->assertNotNull($callHistory);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\CallHistory $callHistory */
        $callHistoryModel = new CallHistory();

        $callHistoryData = factory(CallHistory::class)->make();
        foreach( $callHistoryData->toFillableArray() as $key => $value ) {
            $callHistoryModel->$key = $value;
        }
        $callHistoryModel->save();

        $this->assertNotNull(CallHistory::find($callHistoryModel->id));
    }

}

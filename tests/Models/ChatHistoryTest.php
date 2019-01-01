<?php namespace Tests\Models;

use App\Models\ChatHistory;
use Tests\TestCase;

class ChatHistoryTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\ChatHistory $chatHistory */
        $chatHistory = new ChatHistory();
        $this->assertNotNull($chatHistory);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\ChatHistory $chatHistory */
        $chatHistoryModel = new ChatHistory();

        $chatHistoryData = factory(ChatHistory::class)->make();
        foreach( $chatHistoryData->toFillableArray() as $key => $value ) {
            $chatHistoryModel->$key = $value;
        }
        $chatHistoryModel->save();

        $this->assertNotNull(ChatHistory::find($chatHistoryModel->id));
    }

}

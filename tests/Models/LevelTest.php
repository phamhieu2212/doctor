<?php namespace Tests\Models;

use App\Models\Level;
use Tests\TestCase;

class LevelTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Level $level */
        $level = new Level();
        $this->assertNotNull($level);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Level $level */
        $levelModel = new Level();

        $levelData = factory(Level::class)->make();
        foreach( $levelData->toFillableArray() as $key => $value ) {
            $levelModel->$key = $value;
        }
        $levelModel->save();

        $this->assertNotNull(Level::find($levelModel->id));
    }

}

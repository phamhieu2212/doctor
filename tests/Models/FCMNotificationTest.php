<?php namespace Tests\Models;

use App\Models\FCMNotification;
use Tests\TestCase;

class FCMNotificationTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\FCMNotification $fCMNotification */
        $fCMNotification = new FCMNotification();
        $this->assertNotNull($fCMNotification);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\FCMNotification $fCMNotification */
        $fCMNotificationModel = new FCMNotification();

        $fCMNotificationData = factory(FCMNotification::class)->make();
        foreach( $fCMNotificationData->toFillableArray() as $key => $value ) {
            $fCMNotificationModel->$key = $value;
        }
        $fCMNotificationModel->save();

        $this->assertNotNull(FCMNotification::find($fCMNotificationModel->id));
    }

}

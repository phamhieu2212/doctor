<?php namespace Tests\Repositories;

use App\Models\FCMNotification;
use Tests\TestCase;

class FCMNotificationRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\FCMNotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FCMNotificationRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $fCMNotifications = factory(FCMNotification::class, 3)->create();
        $fCMNotificationIds = $fCMNotifications->pluck('id')->toArray();

        /** @var  \App\Repositories\FCMNotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FCMNotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $fCMNotificationsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(FCMNotification::class, $fCMNotificationsCheck[0]);

        $fCMNotificationsCheck = $repository->getByIds($fCMNotificationIds);
        $this->assertEquals(3, count($fCMNotificationsCheck));
    }

    public function testFind()
    {
        $fCMNotifications = factory(FCMNotification::class, 3)->create();
        $fCMNotificationIds = $fCMNotifications->pluck('id')->toArray();

        /** @var  \App\Repositories\FCMNotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FCMNotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $fCMNotificationCheck = $repository->find($fCMNotificationIds[0]);
        $this->assertEquals($fCMNotificationIds[0], $fCMNotificationCheck->id);
    }

    public function testCreate()
    {
        $fCMNotificationData = factory(FCMNotification::class)->make();

        /** @var  \App\Repositories\FCMNotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FCMNotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $fCMNotificationCheck = $repository->create($fCMNotificationData->toFillableArray());
        $this->assertNotNull($fCMNotificationCheck);
    }

    public function testUpdate()
    {
        $fCMNotificationData = factory(FCMNotification::class)->create();

        /** @var  \App\Repositories\FCMNotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FCMNotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $fCMNotificationCheck = $repository->update($fCMNotificationData, $fCMNotificationData->toFillableArray());
        $this->assertNotNull($fCMNotificationCheck);
    }

    public function testDelete()
    {
        $fCMNotificationData = factory(FCMNotification::class)->create();

        /** @var  \App\Repositories\FCMNotificationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\FCMNotificationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($fCMNotificationData);

        $fCMNotificationCheck = $repository->find($fCMNotificationData->id);
        $this->assertNull($fCMNotificationCheck);
    }

}

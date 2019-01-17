<?php  namespace Tests\Controllers\Admin;

use Tests\TestCase;

class FCMNotificationControllerTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\FCMNotificationController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\FCMNotificationController::class);
        $this->assertNotNull($controller);
    }

    public function setUp()
    {
        parent::setUp();
        $authUser = \App\Models\AdminUser::first();
        $this->be($authUser, 'admins');
    }

    public function testGetList()
    {
        $response = $this->action('GET', 'Admin\FCMNotificationController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\FCMNotificationController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $fCMNotification = factory(\App\Models\FCMNotification::class)->make();
        $this->action('POST', 'Admin\FCMNotificationController@store', [
                '_token' => csrf_token(),
            ] + $fCMNotification->toArray());
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $fCMNotification = factory(\App\Models\FCMNotification::class)->create();
        $this->action('GET', 'Admin\FCMNotificationController@show', [$fCMNotification->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $fCMNotification = factory(\App\Models\FCMNotification::class)->create();

        $name = $faker->name;
        $id = $fCMNotification->id;

        $fCMNotification->name = $name;

        $this->action('PUT', 'Admin\FCMNotificationController@update', [$id], [
                '_token' => csrf_token(),
            ] + $fCMNotification->toArray());
        $this->assertResponseStatus(302);

        $newFCMNotification = \App\Models\FCMNotification::find($id);
        $this->assertEquals($name, $newFCMNotification->name);
    }

    public function testDeleteModel()
    {
        $fCMNotification = factory(\App\Models\FCMNotification::class)->create();

        $id = $fCMNotification->id;

        $this->action('DELETE', 'Admin\FCMNotificationController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);

        $checkFCMNotification = \App\Models\FCMNotification::find($id);
        $this->assertNull($checkFCMNotification);
    }

}

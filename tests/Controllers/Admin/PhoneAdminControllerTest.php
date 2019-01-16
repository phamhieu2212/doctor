<?php  namespace Tests\Controllers\Admin;

use Tests\TestCase;

class PhoneAdminControllerTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\PhoneAdminController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\PhoneAdminController::class);
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
        $response = $this->action('GET', 'Admin\PhoneAdminController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\PhoneAdminController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $phoneAdmin = factory(\App\Models\PhoneAdmin::class)->make();
        $this->action('POST', 'Admin\PhoneAdminController@store', [
                '_token' => csrf_token(),
            ] + $phoneAdmin->toArray());
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $phoneAdmin = factory(\App\Models\PhoneAdmin::class)->create();
        $this->action('GET', 'Admin\PhoneAdminController@show', [$phoneAdmin->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $phoneAdmin = factory(\App\Models\PhoneAdmin::class)->create();

        $name = $faker->name;
        $id = $phoneAdmin->id;

        $phoneAdmin->name = $name;

        $this->action('PUT', 'Admin\PhoneAdminController@update', [$id], [
                '_token' => csrf_token(),
            ] + $phoneAdmin->toArray());
        $this->assertResponseStatus(302);

        $newPhoneAdmin = \App\Models\PhoneAdmin::find($id);
        $this->assertEquals($name, $newPhoneAdmin->name);
    }

    public function testDeleteModel()
    {
        $phoneAdmin = factory(\App\Models\PhoneAdmin::class)->create();

        $id = $phoneAdmin->id;

        $this->action('DELETE', 'Admin\PhoneAdminController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);

        $checkPhoneAdmin = \App\Models\PhoneAdmin::find($id);
        $this->assertNull($checkPhoneAdmin);
    }

}

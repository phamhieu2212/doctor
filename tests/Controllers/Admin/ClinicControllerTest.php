<?php  namespace Tests\Controllers\Admin;

use Tests\TestCase;

class ClinicControllerTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\ClinicController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\ClinicController::class);
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
        $response = $this->action('GET', 'Admin\ClinicController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\ClinicController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $clinic = factory(\App\Models\Clinic::class)->make();
        $this->action('POST', 'Admin\ClinicController@store', [
                '_token' => csrf_token(),
            ] + $clinic->toArray());
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $clinic = factory(\App\Models\Clinic::class)->create();
        $this->action('GET', 'Admin\ClinicController@show', [$clinic->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $clinic = factory(\App\Models\Clinic::class)->create();

        $name = $faker->name;
        $id = $clinic->id;

        $clinic->name = $name;

        $this->action('PUT', 'Admin\ClinicController@update', [$id], [
                '_token' => csrf_token(),
            ] + $clinic->toArray());
        $this->assertResponseStatus(302);

        $newClinic = \App\Models\Clinic::find($id);
        $this->assertEquals($name, $newClinic->name);
    }

    public function testDeleteModel()
    {
        $clinic = factory(\App\Models\Clinic::class)->create();

        $id = $clinic->id;

        $this->action('DELETE', 'Admin\ClinicController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);

        $checkClinic = \App\Models\Clinic::find($id);
        $this->assertNull($checkClinic);
    }

}

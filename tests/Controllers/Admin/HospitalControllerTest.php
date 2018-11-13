<?php  namespace Tests\Controllers\Admin;

use Tests\TestCase;

class HospitalControllerTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\HospitalController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\HospitalController::class);
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
        $response = $this->action('GET', 'Admin\HospitalController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\HospitalController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $hospital = factory(\App\Models\Hospital::class)->make();
        $this->action('POST', 'Admin\HospitalController@store', [
                '_token' => csrf_token(),
            ] + $hospital->toArray());
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $hospital = factory(\App\Models\Hospital::class)->create();
        $this->action('GET', 'Admin\HospitalController@show', [$hospital->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $hospital = factory(\App\Models\Hospital::class)->create();

        $name = $faker->name;
        $id = $hospital->id;

        $hospital->name = $name;

        $this->action('PUT', 'Admin\HospitalController@update', [$id], [
                '_token' => csrf_token(),
            ] + $hospital->toArray());
        $this->assertResponseStatus(302);

        $newHospital = \App\Models\Hospital::find($id);
        $this->assertEquals($name, $newHospital->name);
    }

    public function testDeleteModel()
    {
        $hospital = factory(\App\Models\Hospital::class)->create();

        $id = $hospital->id;

        $this->action('DELETE', 'Admin\HospitalController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);

        $checkHospital = \App\Models\Hospital::find($id);
        $this->assertNull($checkHospital);
    }

}

<?php  namespace Tests\Controllers\Admin;

use Tests\TestCase;

class PointPatientControllerTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\PointPatientController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\PointPatientController::class);
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
        $response = $this->action('GET', 'Admin\PointPatientController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\PointPatientController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $pointPatient = factory(\App\Models\PointPatient::class)->make();
        $this->action('POST', 'Admin\PointPatientController@store', [
                '_token' => csrf_token(),
            ] + $pointPatient->toArray());
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $pointPatient = factory(\App\Models\PointPatient::class)->create();
        $this->action('GET', 'Admin\PointPatientController@show', [$pointPatient->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $pointPatient = factory(\App\Models\PointPatient::class)->create();

        $name = $faker->name;
        $id = $pointPatient->id;

        $pointPatient->name = $name;

        $this->action('PUT', 'Admin\PointPatientController@update', [$id], [
                '_token' => csrf_token(),
            ] + $pointPatient->toArray());
        $this->assertResponseStatus(302);

        $newPointPatient = \App\Models\PointPatient::find($id);
        $this->assertEquals($name, $newPointPatient->name);
    }

    public function testDeleteModel()
    {
        $pointPatient = factory(\App\Models\PointPatient::class)->create();

        $id = $pointPatient->id;

        $this->action('DELETE', 'Admin\PointPatientController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);

        $checkPointPatient = \App\Models\PointPatient::find($id);
        $this->assertNull($checkPointPatient);
    }

}

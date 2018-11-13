<?php  namespace Tests\Controllers\Admin;

use Tests\TestCase;

class SpecialtyControllerTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\SpecialtyController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\SpecialtyController::class);
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
        $response = $this->action('GET', 'Admin\SpecialtyController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\SpecialtyController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $specialty = factory(\App\Models\Specialty::class)->make();
        $this->action('POST', 'Admin\SpecialtyController@store', [
                '_token' => csrf_token(),
            ] + $specialty->toArray());
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $specialty = factory(\App\Models\Specialty::class)->create();
        $this->action('GET', 'Admin\SpecialtyController@show', [$specialty->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $specialty = factory(\App\Models\Specialty::class)->create();

        $name = $faker->name;
        $id = $specialty->id;

        $specialty->name = $name;

        $this->action('PUT', 'Admin\SpecialtyController@update', [$id], [
                '_token' => csrf_token(),
            ] + $specialty->toArray());
        $this->assertResponseStatus(302);

        $newSpecialty = \App\Models\Specialty::find($id);
        $this->assertEquals($name, $newSpecialty->name);
    }

    public function testDeleteModel()
    {
        $specialty = factory(\App\Models\Specialty::class)->create();

        $id = $specialty->id;

        $this->action('DELETE', 'Admin\SpecialtyController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);

        $checkSpecialty = \App\Models\Specialty::find($id);
        $this->assertNull($checkSpecialty);
    }

}

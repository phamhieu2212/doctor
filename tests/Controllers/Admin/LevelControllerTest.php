<?php  namespace Tests\Controllers\Admin;

use Tests\TestCase;

class LevelControllerTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\LevelController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\LevelController::class);
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
        $response = $this->action('GET', 'Admin\LevelController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\LevelController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $level = factory(\App\Models\Level::class)->make();
        $this->action('POST', 'Admin\LevelController@store', [
                '_token' => csrf_token(),
            ] + $level->toArray());
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $level = factory(\App\Models\Level::class)->create();
        $this->action('GET', 'Admin\LevelController@show', [$level->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $level = factory(\App\Models\Level::class)->create();

        $name = $faker->name;
        $id = $level->id;

        $level->name = $name;

        $this->action('PUT', 'Admin\LevelController@update', [$id], [
                '_token' => csrf_token(),
            ] + $level->toArray());
        $this->assertResponseStatus(302);

        $newLevel = \App\Models\Level::find($id);
        $this->assertEquals($name, $newLevel->name);
    }

    public function testDeleteModel()
    {
        $level = factory(\App\Models\Level::class)->create();

        $id = $level->id;

        $this->action('DELETE', 'Admin\LevelController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);

        $checkLevel = \App\Models\Level::find($id);
        $this->assertNull($checkLevel);
    }

}

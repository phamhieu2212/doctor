<?php  namespace Tests\Controllers\Admin;

use Tests\TestCase;

class PlanControllerTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Http\Controllers\Admin\PlanController $controller */
        $controller = \App::make(\App\Http\Controllers\Admin\PlanController::class);
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
        $response = $this->action('GET', 'Admin\PlanController@index');
        $this->assertResponseOk();
    }

    public function testCreateModel()
    {
        $this->action('GET', 'Admin\PlanController@create');
        $this->assertResponseOk();
    }

    public function testStoreModel()
    {
        $plan = factory(\App\Models\Plan::class)->make();
        $this->action('POST', 'Admin\PlanController@store', [
                '_token' => csrf_token(),
            ] + $plan->toArray());
        $this->assertResponseStatus(302);
    }

    public function testEditModel()
    {
        $plan = factory(\App\Models\Plan::class)->create();
        $this->action('GET', 'Admin\PlanController@show', [$plan->id]);
        $this->assertResponseOk();
    }

    public function testUpdateModel()
    {
        $faker = \Faker\Factory::create();

        $plan = factory(\App\Models\Plan::class)->create();

        $name = $faker->name;
        $id = $plan->id;

        $plan->name = $name;

        $this->action('PUT', 'Admin\PlanController@update', [$id], [
                '_token' => csrf_token(),
            ] + $plan->toArray());
        $this->assertResponseStatus(302);

        $newPlan = \App\Models\Plan::find($id);
        $this->assertEquals($name, $newPlan->name);
    }

    public function testDeleteModel()
    {
        $plan = factory(\App\Models\Plan::class)->create();

        $id = $plan->id;

        $this->action('DELETE', 'Admin\PlanController@destroy', [$id], [
                '_token' => csrf_token(),
            ]);
        $this->assertResponseStatus(302);

        $checkPlan = \App\Models\Plan::find($id);
        $this->assertNull($checkPlan);
    }

}

<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatepatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id');
            $table->string('full_name')->nullable();
            $table->date('birth_day')->nullable();
            $table->smallInteger('gender')->nullable();
            $table->string('identification')->nullable();
            $table->string('country')->nullable();
            $table->string('nation')->nullable();
            $table->string('job')->nullable();
            $table->string('email')->nullable();
            $table->integer('province')->nullable();
            $table->integer('district')->nullable();
            $table->integer('ward')->nullable();
            $table->string('address')->nullable();
            $table->string('name_of_relatives')->nullable();
            $table->string('relationship')->nullable();
            $table->string('phone_of_relatives')->nullable();
            $table->bigInteger('profile_image_id')->default(0);

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('patients', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}

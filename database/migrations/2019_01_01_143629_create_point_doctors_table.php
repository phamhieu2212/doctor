<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatepointDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_doctors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_user_id')->comment('id tai khoan bac si');
            $table->bigInteger('point')->comment('diem');

            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('point_doctors', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_doctors');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatedoctorSpecialtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_specialties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_user_id')->comment('id tai khoan');
            $table->unsignedInteger('specialty_id')->comment('id chuyen khoa');

            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('doctor_specialties', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_specialties');
    }
}

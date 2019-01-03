<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatefilePatientImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_patient_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('file_patient_id')->comment('id benh an');
            $table->unsignedInteger('image_id')->comment('id anh');
            $table->string('type')->default('');

            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('file_patient_images', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_patient_images');
    }
}

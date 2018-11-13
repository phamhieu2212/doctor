<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatespecialtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');

            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('specialties', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specialties');
    }
}

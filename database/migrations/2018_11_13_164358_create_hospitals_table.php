<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatehospitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');

            $table->string('address');

            $table->string('phone');

            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('hospitals', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hospitals');
    }
}

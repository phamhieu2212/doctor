<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateclinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_user_id')->comment('id tai khoan');
            $table->string('name');
            $table->integer('price');
            $table->string('address');
            $table->unsignedTinyInteger('status');

            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('clinics', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clinics');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateplansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_user_id')->comment('id tai khoan bs');
            $table->unsignedInteger('user_id')->comment('id tai khoan bn')->nullable();
            $table->unsignedInteger('clinic_id')->comment('id tai khoan pk');
            $table->integer('price');
            $table->smallInteger('status')->default(0);
            $table->dateTime('started_at');
            $table->dateTime('ended_at');

            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('plans', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}

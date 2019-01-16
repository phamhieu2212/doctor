<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateadminStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_statistics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_user_id')->comment('id tai khoan bac si');
            $table->unsignedInteger('conversation_id')->comment('id tai khoan bac si');
            $table->bigInteger('total')->comment('diem');
            $table->bigInteger('price')->comment('diem');
            $table->date('date')->comment('diem');
            $table->integer('time_call')->default(0);
            $table->tinyInteger('type');
            $table->tinyInteger('is_patient_new')->default(0);

            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('admin_statistics', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_statistics');
    }
}

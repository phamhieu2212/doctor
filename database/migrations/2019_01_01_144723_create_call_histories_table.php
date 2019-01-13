<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatecallHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->comment('id tai khoan benh nhan');
            $table->unsignedInteger('admin_user_id')->comment('id tai khoan bac si');
            $table->string('caller')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('type')->nullable();
            $table->integer('is_read')->nullable()->default(0);
            $table->integer('rate')->comment('diem')->default(0);
            $table->text('content')->nullable();

            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('call_histories', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_histories');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatechatHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->comment('id tai khoan benh nhan');
            $table->unsignedInteger('admin_user_id')->comment('id tai khoan bac si');
            $table->unsignedInteger('file_patient_id')->comment('id benh an');
            $table->integer('rate')->comment('diem')->default(0);
            $table->text('content')->nullable();

            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('chat_histories', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_histories');
    }
}

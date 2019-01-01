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

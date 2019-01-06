<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatedevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('device_id');
            $table->smallInteger('type');
            $table->bigInteger('user_id');

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('devices', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}

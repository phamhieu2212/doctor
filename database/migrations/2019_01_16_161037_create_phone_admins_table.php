<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatephoneAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phone');

            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('phone_admins', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phone_admins');
    }
}

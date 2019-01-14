<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->comment('id tai khoan bac si');
            $table->string('order_code');
            $table->integer('price');
            $table->integer('status')->default(0);

            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('payments', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}

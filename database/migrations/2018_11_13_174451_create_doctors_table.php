<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatedoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedInteger('admin_user_id')->comment('id tai khoan');
            $table->unsignedInteger('hospital_id')->comment('id benh vien');
            $table->unsignedInteger('level_id')->comment('id hoc ham');
            $table->string('sub_phone')->nullable();
            $table->smallInteger('gender')->default(1);     // 1 = Male, 0 = Female
            $table->integer('price_chat')->default(0);
            $table->integer('price_call')->default(0);
            $table->date('birthday')->nullable()->default(null);

            $table->string('address')->nullable()->default('');
            $table->string('city')->nullable()->default('');
            $table->string('position')->nullable()->default('');
            $table->string('bank_name')->nullable();
            $table->string('bank_number')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('bank_owner')->nullable();
            $table->text('experience')->nullable()->default('');
            $table->text('description')->nullable()->default('');

            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('doctors', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}

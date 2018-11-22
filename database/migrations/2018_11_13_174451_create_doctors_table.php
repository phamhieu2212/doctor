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
            $table->unsignedInteger('hospital_id')->comment('id tai khoan');
            $table->smallInteger('gender')->default(1);     // 1 = Male, 0 = Female
            $table->date('birthday')->nullable()->default(null);

            $table->string('address')->nullable()->default('');
            $table->string('city')->nullable()->default('');
            $table->string('position')->nullable()->default('');
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

<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatefilePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('title');
            $table->unsignedInteger('user_id')->comment('id tai khoan');
            $table->date('started_at');
            $table->text('description')->nullable();

            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('file_patients', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_patients');
    }
}

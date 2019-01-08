<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatefcmNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fcm_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id');
            $table->smallInteger('user_type');
            $table->text('data');
            $table->dateTime('sent_at')->nullable()->default(null);
            $table->boolean('is_read')->default(false);

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('fcm_notifications', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fcm_notifications');
    }
}

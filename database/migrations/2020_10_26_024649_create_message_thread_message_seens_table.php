<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageThreadMessageSeensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_thread_message_seens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_thread_id');
            $table->foreign('message_thread_id')->references('id')->on('message_threads')->onDelete('cascade');
            $table->unsignedBigInteger('thread_message_id');
            $table->foreign('thread_message_id')->references('id')->on('message_thread_messages')->onDelete('cascade');
            $table->dateTime('time_seen', 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_thread_message_seens');
    }
}

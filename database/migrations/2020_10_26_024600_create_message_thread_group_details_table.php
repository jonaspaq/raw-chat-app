<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageThreadGroupDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_thread_group_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_thread_id');
            $table->foreign('message_thread_id')->references('id')->on('message_threads')->onDelete('cascade');
            $table->text('group_name');
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
        Schema::dropIfExists('message_thread_group_details');
    }
}

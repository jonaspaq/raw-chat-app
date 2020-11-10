<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLastMessageIdToMessageThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_threads', function (Blueprint $table) {
            $table->unsignedBigInteger('last_message_id')->nullable();
            $table->foreign('last_message_id')->references('id')->on('message_thread_messages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_threads', function (Blueprint $table) {
            $table->dropColumn('last_message_id');
        });
    }
}

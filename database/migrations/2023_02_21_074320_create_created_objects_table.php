<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('created_objects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('object_id');
            $table->unsignedBigInteger('chat_id');
            $table->foreign('chat_id')->on('chats')->references('id')->onDelete('cascade');
            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('trigger_id');
            $table->foreign('trigger_id')->on('triggers')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('created_objects');
    }
};

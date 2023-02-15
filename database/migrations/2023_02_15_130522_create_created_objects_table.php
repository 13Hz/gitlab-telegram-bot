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
            $table->integer('chat_id');
            $table->unsignedBigInteger('message_id');
            $table->string('object_type');
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

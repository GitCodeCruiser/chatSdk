<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delete_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->references('id')->on('messages')->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->references('id')->on('rooms')->onDelete('cascade');
            $table->foreignId('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('delete_messages');
    }
};

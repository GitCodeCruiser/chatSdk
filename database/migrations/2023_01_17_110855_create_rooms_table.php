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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Personal room');
            $table->string('profile_picture')->nullable()->comment('Group Chat Picture');
            $table->longText('description')->nullable();
            $table->tinyInteger('type')->default(0)->comment('Group Chat or Private Chat');
            $table->tinyInteger('private_chat_status')->default('1')->comment('1 for friend and 0 for request chat');
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
        Schema::dropIfExists('rooms');
    }
};

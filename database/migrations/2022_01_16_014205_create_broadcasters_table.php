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
        Schema::create('broadcasters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('twitch_id')->unique();
            $table->string('name');
            $table->string('display_name');
            $table->string('logo')->nullable();
            $table->json('old_names')->nullable();
            $table->unsignedBigInteger('video_count')->default(0);
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
        Schema::dropIfExists('broadcasters');
    }
};

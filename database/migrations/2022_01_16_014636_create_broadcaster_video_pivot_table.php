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
        Schema::create('broadcaster_video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broadcaster_id')->constrained('broadcasters', 'id')->onDelete('cascade');
            $table->foreignId('video_id')->constrained('videos', 'id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('broadcaster_video');
    }
};

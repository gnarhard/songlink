<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('song_links', function (Blueprint $table) {
            $table->id();
            $table->string('entity_unique_id')->unique();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->json('links');
            $table->string('raw_response')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('song_links');
    }
};

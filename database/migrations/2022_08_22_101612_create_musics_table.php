<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musics', function (Blueprint $table) {
            $table->id();
            $table->string('music_name');
            $table->string('music_file');
            $table->longText('music_image');
            $table->integer('music_view_count');
            $table->integer('music_like_count');
            $table->integer('music_download_count');
            $table->integer('music_feature');
            $table->integer('music_status');
            $table->string('music_type');
            $table->text('music_link');
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
        Schema::dropIfExists('musics');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MusicsYtb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musics_ytb', function (Blueprint $table) {
            $table->id();
            $table->string('music_id_ytb');
            $table->text('music_url_link_audio_ytb');
            $table->integer('music_view_count');
            $table->string('music_thumbnail_link');
            $table->string('music_file')->nullable();
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
        //
    }
}

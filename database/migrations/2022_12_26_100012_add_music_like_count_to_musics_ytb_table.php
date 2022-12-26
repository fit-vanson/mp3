<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMusicLikeCountToMusicsYtbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('musics_ytb', function (Blueprint $table) {
            $table->integer('music_like_count')->after('music_view_count')->default(1000);
            $table->integer('music_download_count')->after('music_view_count')->default(1000);
            $table->integer('expire')->after('music_url_link_audio_ytb');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('musics_ytb', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToMusicsYtbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('musics_ytb', function (Blueprint $table) {
            $table->string('music_title')->after('music_thumbnail_link');
            $table->text('music_description')->after('music_thumbnail_link');
            $table->text('music_keywords')->after('music_thumbnail_link');
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

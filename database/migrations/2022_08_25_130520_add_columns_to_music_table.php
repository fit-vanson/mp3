<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToMusicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('musics', function (Blueprint $table) {
            $table->string('music_link_1')->after('music_type')->nullable();
            $table->string('music_link_2')->after('music_link_1')->nullable();
            $table->string('music_id_ytb')->after('music_link_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('musics', function (Blueprint $table) {

        });
    }
}

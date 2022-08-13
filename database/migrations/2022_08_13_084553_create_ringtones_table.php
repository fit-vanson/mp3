<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRingtonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ringtones', function (Blueprint $table) {
            $table->id();
            $table->string('ringtone_name');
            $table->longText('ringtone_file');
            $table->integer('ringtone_view_count');
            $table->integer('ringtone_like_count');
            $table->integer('ringtone_download_count');
            $table->integer('ringtone_feature');
            $table->integer('ringtone_status');
            $table->string('ringtone_type');
            $table->string('ringtone_extension');
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
        Schema::dropIfExists('ringtones');
    }
}

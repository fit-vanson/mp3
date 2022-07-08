<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWallpapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallpapers', function (Blueprint $table) {
            $table->id();
            $table->string('wallpaper_name');
            $table->longText('wallpaper_image');
            $table->integer('wallpaper_view_count');
            $table->integer('wallpaper_like_count');
            $table->integer('wallpaper_download_count');
            $table->integer('wallpaper_feature');
            $table->string('image_extension')->default('image/jpeg');
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
        Schema::dropIfExists('wallpapers');
    }
}

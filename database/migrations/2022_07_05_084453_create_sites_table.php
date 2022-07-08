<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('ad_switch')->default(0);
            $table->tinyInteger('load_view_by')->default(1);
            $table->string('site_name')->nullable();
            $table->string('site_web')->nullable();
            $table->string('site_image')->nullable();
            $table->text('site_feature_images')->nullable();
            $table->string('site_header_title')->nullable();
            $table->text('site_header_content')->nullable();
            $table->string('site_body_title')->nullable();
            $table->text('site_body_content')->nullable();
            $table->string('site_footer_title')->nullable();
            $table->text('site_footer_content')->nullable();
            $table->text('site_policy')->nullable();
            $table->text('site_ads')->nullable();
            $table->string('site_direct_link')->nullable();
            $table->string('site_chplay_link')->nullable();
            $table->integer('site_view_page')->nullable();
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
        Schema::dropIfExists('sites');
    }
}

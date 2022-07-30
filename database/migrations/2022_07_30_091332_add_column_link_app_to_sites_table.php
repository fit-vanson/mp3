<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLinkAppToSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->string('site_chplay_link')->after('site_direct_link')->nullable();
            $table->string('site_oppo_link')->after('site_direct_link')->nullable();
            $table->string('site_vivo_link')->after('site_direct_link')->nullable();
            $table->string('site_xiaomi_link')->after('site_direct_link')->nullable();
            $table->string('site_huawei_link')->after('site_direct_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            //
        });
    }
}

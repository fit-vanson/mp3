<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCountToGoogleAds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('google_ads', function (Blueprint $table) {
            $table->integer('count')->after('is_Devices')->default(0);
            $table->dropColumn('value');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('google_ads', function (Blueprint $table) {
            //
        });
    }
}

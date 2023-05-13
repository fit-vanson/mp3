<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToGoogleAds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('google_ads', function (Blueprint $table) {
            $table->string('url_block')->after('name')->nullable();
            $table->text('html')->after('name')->nullable();
            $table->text('devices_value')->after('name')->nullable();
            $table->text('country_value')->after('name')->nullable();
            $table->integer('is_Devices')->default(0)->after('name')->comment('check Devices, nếu là 0 thì chuyển sang Devices. Nếu là 1 thì chuyển sang quốc gia');
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

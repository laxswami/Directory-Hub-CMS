<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogleAnalyticsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('setting_site_google_analytic_enabled')->default(0)->comment('1:on 0:off');
            $table->string('setting_site_google_analytic_tracking_id')->nullable();
            $table->integer('setting_site_google_analytic_not_track_admin')->default(1)->comment('1:track 0:no track');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('setting_site_google_analytic_not_track_admin');
            $table->dropColumn('setting_site_google_analytic_tracking_id');
        });
    }
}

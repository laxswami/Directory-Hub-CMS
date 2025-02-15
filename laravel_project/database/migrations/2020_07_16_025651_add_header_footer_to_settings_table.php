<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeaderFooterToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('setting_site_header_enabled')->default(0)->comment('1:on 0:off');
            $table->longText('setting_site_header')->nullable();

            $table->integer('setting_site_footer_enabled')->default(0)->comment('1:on 0:off');
            $table->longText('setting_site_footer')->nullable();
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
            $table->dropColumn('setting_site_header');
            $table->dropColumn('setting_site_footer');

            $table->dropColumn('setting_site_header_enabled');
            $table->dropColumn('setting_site_footer_enabled');
        });
    }
}

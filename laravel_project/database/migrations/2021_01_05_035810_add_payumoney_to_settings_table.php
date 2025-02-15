<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayumoneyToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('setting_site_payumoney_enable')->default(0)->comment('0:disable 1:enable');
            $table->string('setting_site_payumoney_mode')->nullable();
            $table->string('setting_site_payumoney_merchant_key')->nullable();
            $table->string('setting_site_payumoney_salt')->nullable();
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
            $table->dropColumn('setting_site_payumoney_salt');
            $table->dropColumn('setting_site_payumoney_merchant_key');
            $table->dropColumn('setting_site_payumoney_mode');
            $table->dropColumn('setting_site_payumoney_enable');
        });
    }
}

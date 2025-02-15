<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRazorpayToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('setting_site_razorpay_enable')->default(0)->comment('0:disable 1:enable');
            $table->string('setting_site_razorpay_api_key')->nullable();
            $table->string('setting_site_razorpay_api_secret')->nullable();
            $table->string('setting_site_razorpay_currency')->default('INR');

            $table->integer('setting_site_paypal_enable')->default(0)->comment('0:disable 1:enable');
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
            $table->dropColumn('setting_site_paypal_enable');

            $table->dropColumn('setting_site_razorpay_currency');
            $table->dropColumn('setting_site_razorpay_api_secret');
            $table->dropColumn('setting_site_razorpay_api_key');
            $table->dropColumn('setting_site_razorpay_enable');
        });
    }
}

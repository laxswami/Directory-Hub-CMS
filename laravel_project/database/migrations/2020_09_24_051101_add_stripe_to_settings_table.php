<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStripeToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('setting_site_stripe_enable')->default(0)->comment('0:disable 1:enable');
            $table->string('setting_site_stripe_publishable_key')->nullable();
            $table->string('setting_site_stripe_secret_key')->nullable();
            $table->string('setting_site_stripe_webhook_signing_secret')->nullable();
            $table->string('setting_site_stripe_currency')->default('usd');
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
            $table->dropColumn('setting_site_stripe_enable');
            $table->dropColumn('setting_site_stripe_publishable_key');
            $table->dropColumn('setting_site_stripe_secret_key');
            $table->dropColumn('setting_site_stripe_webhook_signing_secret');
            $table->dropColumn('setting_site_stripe_currency');
        });
    }
}

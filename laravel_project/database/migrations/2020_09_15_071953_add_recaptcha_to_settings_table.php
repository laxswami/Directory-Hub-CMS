<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecaptchaToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('setting_site_recaptcha_login_enable')->default(0)->comment('0:disable 1:enable');
            $table->integer('setting_site_recaptcha_sign_up_enable')->default(0)->comment('0:disable 1:enable');
            $table->integer('setting_site_recaptcha_contact_enable')->default(0)->comment('0:disable 1:enable');

            $table->string('setting_site_recaptcha_site_key')->nullable();
            $table->string('setting_site_recaptcha_secret_key')->nullable();
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
            $table->dropColumn('setting_site_recaptcha_secret_key');
            $table->dropColumn('setting_site_recaptcha_site_key');
            $table->dropColumn('setting_site_recaptcha_contact_enable');
            $table->dropColumn('setting_site_recaptcha_sign_up_enable');
            $table->dropColumn('setting_site_recaptcha_login_enable');
        });
    }
}

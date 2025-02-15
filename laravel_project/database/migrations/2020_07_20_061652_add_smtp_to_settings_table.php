<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSmtpToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {

            $table->integer('settings_site_smtp_enabled')->default(0)->comment('0:disabled 1:enabled');

            $table->string('settings_site_smtp_sender_name')->nullable();
            $table->string('settings_site_smtp_sender_email')->nullable();

            $table->string('settings_site_smtp_host')->nullable();
            $table->integer('settings_site_smtp_port')->nullable();
            $table->integer('settings_site_smtp_encryption')->default(0)->comment('0:null 1:ssl 2:tls');

            $table->string('settings_site_smtp_username')->nullable();
            $table->string('settings_site_smtp_password')->nullable();
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
            $table->dropColumn('settings_site_smtp_enabled');
            $table->dropColumn('settings_site_smtp_sender_name');
            $table->dropColumn('settings_site_smtp_sender_email');
            $table->dropColumn('settings_site_smtp_host');
            $table->dropColumn('settings_site_smtp_port');
            $table->dropColumn('settings_site_smtp_encryption');
            $table->dropColumn('settings_site_smtp_username');
            $table->dropColumn('settings_site_smtp_password');
        });
    }
}

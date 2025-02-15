<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsLicenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_license', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('setting_id');

            $table->string('settings_license_purchase_code')->nullable();
            $table->string('settings_license_codecanyon_username')->nullable();
            $table->string('settings_license_domain_verify_token')->nullable();

            $table->timestamps();
        });

        $setting_license = new \App\SettingLicense(array(
            'setting_id' => 1,
        ));
        $setting_license->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings_license');
    }
}

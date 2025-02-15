<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThemeIdToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('setting_site_active_theme_id');
        });

        /**
         * Start initial setting_site_active_theme_id
         */
        $default_frontend_theme = \App\Theme::where('theme_type', \App\Theme::THEME_TYPE_FRONTEND)
            ->where('theme_status', \App\Theme::THEME_STATUS_ACTIVE)
            ->where('theme_system_default', \App\Theme::THEME_SYSTEM_DEFAULT_YES)->first();

        $settings = \App\Setting::find(1);
        if($settings)
        {
            $settings->setting_site_active_theme_id = $default_frontend_theme->id;
            $settings->save();
        }
        /**
         * End initial setting_site_active_theme_id
         */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('setting_site_active_theme_id');
        });
    }
}

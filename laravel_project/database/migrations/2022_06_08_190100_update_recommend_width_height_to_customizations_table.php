<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRecommendWidthHeightToCustomizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customizations', function (Blueprint $table) {
            // nothing to migrate
        });

        /**
         * Start initial the values for system default theme
         */
        // homepage background image recommend width and height
        $system_default_theme = \App\Theme::where('theme_system_default', \App\Theme::THEME_SYSTEM_DEFAULT_YES)
            ->first();

        $customization_homepage_header_background_image = $system_default_theme->customizations()
            ->where('customization_key', \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE)->first();
        $customization_homepage_header_background_image->customization_recommend_width_px = "1425";
        $customization_homepage_header_background_image->customization_recommend_height_px = "750";
        $customization_homepage_header_background_image->save();

        // innerpage background image recommend width and height
        $customization_innerpage_header_background_image = $system_default_theme->customizations()
            ->where('customization_key', \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)->first();
        $customization_innerpage_header_background_image->customization_recommend_width_px = "1425";
        $customization_innerpage_header_background_image->customization_recommend_height_px = "500";
        $customization_innerpage_header_background_image->save();
        /**
         * End initial the values for system default theme
         */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customizations', function (Blueprint $table) {
            // nothing to migrate
        });
    }
}

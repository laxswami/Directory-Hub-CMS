<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThemeIdToCustomizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customizations', function (Blueprint $table) {
            $table->string('customization_default_value')->nullable();
            $table->integer('theme_id');
        });

        /**
         * Start initial customizations records
         */
        $default_frontend_theme = \App\Theme::where('theme_type', \App\Theme::THEME_TYPE_FRONTEND)
            ->where('theme_status', \App\Theme::THEME_STATUS_ACTIVE)
            ->where('theme_system_default', \App\Theme::THEME_SYSTEM_DEFAULT_YES)->first();
        $customizations = \App\Customization::all();
        foreach($customizations as $customization_key => $customization)
        {
            if($customization->customization_key == \App\Customization::SITE_PRIMARY_COLOR)
            {
                $customization->customization_default_value = \App\Customization::SITE_PRIMARY_COLOR_DEFAULT;
            }
            elseif($customization->customization_key == \App\Customization::SITE_HEADER_BACKGROUND_COLOR)
            {
                $customization->customization_default_value = \App\Customization::SITE_HEADER_BACKGROUND_COLOR_DEFAULT;
            }
            elseif($customization->customization_key == \App\Customization::SITE_FOOTER_BACKGROUND_COLOR)
            {
                $customization->customization_default_value = \App\Customization::SITE_FOOTER_BACKGROUND_COLOR_DEFAULT;
            }
            elseif($customization->customization_key == \App\Customization::SITE_HEADER_FONT_COLOR)
            {
                $customization->customization_default_value = \App\Customization::SITE_HEADER_FONT_COLOR_DEFAULT;
            }
            elseif($customization->customization_key == \App\Customization::SITE_FOOTER_FONT_COLOR)
            {
                $customization->customization_default_value = \App\Customization::SITE_FOOTER_FONT_COLOR_DEFAULT;
            }
            elseif($customization->customization_key == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE)
            {
                $customization->customization_default_value = \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_DEFAULT;
            }
            elseif($customization->customization_key == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_COLOR)
            {
                $customization->customization_default_value = null;
            }
            elseif($customization->customization_key == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE)
            {
                $customization->customization_default_value = null;
            }
            elseif($customization->customization_key == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            {
                $customization->customization_default_value = null;
            }
            elseif($customization->customization_key == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
            {
                $customization->customization_default_value = \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_DEFAULT;
            }
            elseif($customization->customization_key == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
            {
                $customization->customization_default_value = null;
            }
            elseif($customization->customization_key == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
            {
                $customization->customization_default_value = null;
            }
            elseif($customization->customization_key == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            {
                $customization->customization_default_value = null;
            }
            elseif($customization->customization_key == \App\Customization::SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR)
            {
                $customization->customization_default_value = \App\Customization::SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR_DEFAULT;
            }
            elseif($customization->customization_key == \App\Customization::SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            {
                $customization->customization_default_value = \App\Customization::SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR_DEFAULT;
            }
            elseif($customization->customization_key == \App\Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
            {
                $customization->customization_default_value = \App\Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR_DEFAULT;
            }
            elseif($customization->customization_key == \App\Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            {
                $customization->customization_default_value = \App\Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR_DEFAULT;
            }

            $customization->theme_id = $default_frontend_theme->id;
            $customization->save();
        }
        /**
         * End initial customizations records
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
            $table->dropColumn('theme_id');
            $table->dropColumn('customization_default_value');
        });
    }
}

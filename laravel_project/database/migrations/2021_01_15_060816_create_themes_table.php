<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class CreateThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('theme_identifier')->unique()->comment('an unique column');
            $table->string('theme_name');
            $table->string('theme_preview_image')->nullable();
            $table->string('theme_author')->nullable();
            $table->text('theme_description')->nullable();
            $table->integer('theme_type')->comment('1:frontend theme, 2:admin theme, 3:user theme');
            $table->integer('theme_status')->default(2)->comment('1:active 2:inactive');
            $table->integer('theme_system_default')->default(2)->comment('1:system default theme, 2:not system default theme');

            $table->timestamps();
        });

        /**
         * Start make additional unique column
         */
        $default_theme = new \App\Theme(array(
            'theme_identifier' => "lduruo10_dh_frontend_default",
            'theme_name' => "Directory Hub",
            'theme_preview_image' => 'system_default_frontend_theme_preview.jpg',
            'theme_author' => "lduruo10",
            'theme_description' => __('theme_directory_hub.theme-description-directory-hub'),
            'theme_type' => \App\Theme::THEME_TYPE_FRONTEND,
            'theme_status' => \App\Theme::THEME_STATUS_ACTIVE,
            'theme_system_default' => \App\Theme::THEME_SYSTEM_DEFAULT_YES,
        ));
        $default_theme->save();
        /**
         * End make additional unique column
         */

        /**
         * Start create theme_assets folder and theme_views folder
         */
        if(!File::exists(base_path() . DIRECTORY_SEPARATOR . \App\Theme::THEME_VIEWS))
        {
            File::makeDirectory(base_path() . DIRECTORY_SEPARATOR . \App\Theme::THEME_VIEWS);
        }
        if(!File::exists(base_path() . DIRECTORY_SEPARATOR . \App\Theme::THEME_VIEWS . DIRECTORY_SEPARATOR . \App\Theme::THEME_VIEWS_FRONTEND))
        {
            File::makeDirectory(base_path() . DIRECTORY_SEPARATOR . \App\Theme::THEME_VIEWS . DIRECTORY_SEPARATOR . \App\Theme::THEME_VIEWS_FRONTEND);
        }
        if(!File::exists(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . \App\Theme::THEME_ASSETS))
        {
            File::makeDirectory(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . \App\Theme::THEME_ASSETS);
        }
        if(!File::exists(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . \App\Theme::THEME_ASSETS . DIRECTORY_SEPARATOR . \App\Theme::THEME_ASSETS_FRONTEND))
        {
            File::makeDirectory(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . \App\Theme::THEME_ASSETS . DIRECTORY_SEPARATOR . \App\Theme::THEME_ASSETS_FRONTEND);
        }
        /**
         * End create theme_assets folder and theme_views folder
         */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('themes');
    }
}

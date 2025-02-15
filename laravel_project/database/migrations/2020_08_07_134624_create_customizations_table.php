<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCustomizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customizations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('customization_key')->nullable();
            $table->string('customization_value')->nullable();

            $table->timestamps();
        });

        DB::table('customizations')->insert([
            [
                'customization_key' => \App\Customization::SITE_PRIMARY_COLOR,
                'customization_value' => \App\Customization::SITE_PRIMARY_COLOR_DEFAULT,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_HEADER_BACKGROUND_COLOR,
                'customization_value' => \App\Customization::SITE_HEADER_BACKGROUND_COLOR_DEFAULT,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_FOOTER_BACKGROUND_COLOR,
                'customization_value' => \App\Customization::SITE_FOOTER_BACKGROUND_COLOR_DEFAULT,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_HEADER_FONT_COLOR,
                'customization_value' => \App\Customization::SITE_HEADER_FONT_COLOR_DEFAULT,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_FOOTER_FONT_COLOR,
                'customization_value' => \App\Customization::SITE_FOOTER_FONT_COLOR_DEFAULT,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE,
                'customization_value' => \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_DEFAULT,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_COLOR,
                'customization_value' => null,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE,
                'customization_value' => null,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO,
                'customization_value' => null,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE,
                'customization_value' => \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_DEFAULT,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR,
                'customization_value' => null,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE,
                'customization_value' => null,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO,
                'customization_value' => null,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR,
                'customization_value' => \App\Customization::SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR_DEFAULT,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR,
                'customization_value' => \App\Customization::SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR_DEFAULT,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR,
                'customization_value' => \App\Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR_DEFAULT,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'customization_key' => \App\Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR,
                'customization_value' => \App\Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR_DEFAULT,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customizations');
    }
}

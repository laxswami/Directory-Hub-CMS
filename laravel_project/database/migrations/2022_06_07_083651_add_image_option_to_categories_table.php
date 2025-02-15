<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageOptionToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('category_image')->nullable();
            $table->integer('category_thumbnail_type')->default(1)->comment('1:icon, 2:image');
            $table->integer('category_header_background_type')->default(1)->comment('1:inherited_background, 2:color_background, 3:image_background, 4:youtube_video_background');

            $table->string('category_header_background_color')->nullable();
            $table->string('category_header_background_image')->nullable();
            $table->string('category_header_background_youtube_video')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('category_header_background_youtube_video');
            $table->dropColumn('category_header_background_image');
            $table->dropColumn('category_header_background_color');
            $table->dropColumn('category_header_background_type');
            $table->dropColumn('category_thumbnail_type');
            $table->dropColumn('category_image');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemImageGalleryThumbNameToItemImageGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_image_galleries', function (Blueprint $table) {
            $table->string('item_image_gallery_thumb_name')->nullable()
                ->after('item_image_gallery_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_image_galleries', function (Blueprint $table) {
            $table->dropColumn('item_image_gallery_thumb_name');
        });
    }
}

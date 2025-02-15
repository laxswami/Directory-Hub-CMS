<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagesToImportItemDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_item_data', function (Blueprint $table) {
            $table->text('import_item_data_item_image')->nullable();
            $table->text('import_item_data_item_image_galleries')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_item_data', function (Blueprint $table) {
            $table->dropColumn('import_item_data_item_image_galleries');
            $table->dropColumn('import_item_data_item_image');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHoursToImportItemDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_item_data', function (Blueprint $table) {
            $table->string('import_item_data_item_hour_time_zone')->nullable();
            $table->integer('import_item_data_item_hour_show_hours')->nullable()->comment('1:show 2:not show');
            $table->text('import_item_data_item_hours')->nullable();
            $table->text('import_item_data_item_image_hour_exceptions')->nullable();
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
            $table->dropColumn('import_item_data_item_image_hour_exceptions');
            $table->dropColumn('import_item_data_item_hours');
            $table->dropColumn('import_item_data_item_hour_show_hours');
            $table->dropColumn('import_item_data_item_hour_time_zone');
        });
    }
}

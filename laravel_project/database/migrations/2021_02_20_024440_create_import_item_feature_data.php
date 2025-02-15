<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportItemFeatureData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_item_feature_data', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('import_item_data_id');
            $table->integer('import_item_feature_data_custom_field_id');
            $table->text('import_item_feature_data_item_feature_value')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_item_feature_data');
    }
}

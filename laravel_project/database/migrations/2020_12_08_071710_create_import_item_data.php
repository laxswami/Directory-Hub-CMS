<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportItemData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_item_data', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('import_item_data_markup')->nullable();

            $table->string('import_item_data_item_title')->nullable();
            $table->string('import_item_data_item_slug')->nullable();
            $table->string('import_item_data_item_address')->nullable();
            $table->string('import_item_data_city')->nullable();
            $table->string('import_item_data_state')->nullable();
            $table->string('import_item_data_country')->nullable();
            $table->string('import_item_data_item_lat')->nullable();
            $table->string('import_item_data_item_lng')->nullable();
            $table->string('import_item_data_item_postal_code')->nullable();
            $table->text('import_item_data_item_description')->nullable();
            $table->string('import_item_data_item_phone')->nullable();
            $table->string('import_item_data_item_website')->nullable();
            $table->string('import_item_data_item_social_facebook')->nullable();
            $table->string('import_item_data_item_social_twitter')->nullable();
            $table->string('import_item_data_item_social_linkedin')->nullable();
            $table->string('import_item_data_item_youtube_id')->nullable();

            $table->integer('import_item_data_process_status')->default(1)->comment('1:not processed 2:processed success 3:processed error');
            $table->integer('import_item_data_item_id')->nullable();
            $table->integer('import_item_data_source')->default(1)->comment('1:csv file 2:google place');
            $table->text('import_item_data_process_error_log')->nullable();

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
        Schema::dropIfExists('import_item_data');
    }
}

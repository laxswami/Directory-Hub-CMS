<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemSectionCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_section_collections', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('item_section_id');
            $table->integer('item_section_collection_order');
            $table->string('item_section_collection_collectible_type');
            $table->integer('item_section_collection_collectible_id');

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
        Schema::dropIfExists('item_section_collections');
    }
}

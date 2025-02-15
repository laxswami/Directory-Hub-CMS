<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemImageMediumSmallToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('item_image_medium')->nullable();
            $table->string('item_image_small')->nullable();
            $table->string('item_image_tiny')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('item_image_tiny');
            $table->dropColumn('item_image_small');
            $table->dropColumn('item_image_medium');
        });
    }
}

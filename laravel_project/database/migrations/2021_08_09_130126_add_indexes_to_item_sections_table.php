<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToItemSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_sections', function (Blueprint $table) {
            $table->index('item_id');

            $table->index('item_section_position');
            $table->index('item_section_order');
            $table->index('item_section_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_sections', function (Blueprint $table) {
            $table->dropIndex('item_sections_item_id_index');

            $table->dropIndex('item_sections_item_section_position_index');
            $table->dropIndex('item_sections_item_section_order_index');
            $table->dropIndex('item_sections_item_section_status_index');
        });
    }
}

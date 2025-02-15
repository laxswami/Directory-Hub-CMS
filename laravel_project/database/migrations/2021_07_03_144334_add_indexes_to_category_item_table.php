<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToCategoryItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_item', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('item_id');

            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_item', function (Blueprint $table) {
            $table->dropIndex('category_item_category_id_index');
            $table->dropIndex('category_item_item_id_index');

            $table->dropIndex('category_item_created_at_index');
            $table->dropIndex('category_item_updated_at_index');
        });
    }
}

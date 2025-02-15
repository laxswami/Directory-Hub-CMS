<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $items = \App\Item::all();

        $item_slug_array = array();
        foreach($items as $items_key => $item)
        {
            if(array_key_exists($item->item_slug, $item_slug_array))
            {
                $item->deleteItem();
            }
            else
            {
                $item_slug_array[$item->item_slug] = $item->id;
            }
        }

        Schema::table('items', function (Blueprint $table) {
            $table->unique('item_slug');

            $table->index('item_status');
            $table->index('item_type');

            $table->index('user_id');
            $table->index('city_id');
            $table->index('state_id');
            $table->index('country_id');

            $table->index('item_featured');
            $table->index('item_featured_by_admin');

            $table->index('item_average_rating');

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
        Schema::table('items', function (Blueprint $table) {
            $table->dropUnique('items_item_slug_unique');

            $table->dropIndex('items_item_status_index');
            $table->dropIndex('items_item_type_index');
            $table->dropIndex('items_user_id_index');
            $table->dropIndex('items_city_id_index');
            $table->dropIndex('items_state_id_index');
            $table->dropIndex('items_country_id_index');
            $table->dropIndex('items_item_featured_index');
            $table->dropIndex('items_item_featured_by_admin_index');

            $table->dropIndex('items_item_average_rating_index');

            $table->dropIndex('items_created_at_index');
            $table->dropIndex('items_updated_at_index');
        });
    }
}

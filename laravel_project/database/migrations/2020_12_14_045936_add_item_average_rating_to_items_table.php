<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemAverageRatingToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('item_average_rating', 2, 1)->nullable();
        });

        // update item_average_rating of all items
        $items = \App\Item::all();

        foreach($items as $items_key => $item)
        {
            if($item->getCountRating() > 0)
            {
                $item_average_rating = $item->getAverageRating();
                $item->item_average_rating = $item_average_rating;
                $item->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('item_average_rating');
        });
    }
}

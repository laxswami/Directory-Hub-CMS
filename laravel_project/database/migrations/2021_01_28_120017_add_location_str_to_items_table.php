<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationStrToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('item_location_str')->nullable();
        });

        $items = \App\Item::all();

        foreach($items as $items_key => $item)
        {
            $item->item_location_str = $item->city->city_name . ' ' . $item->state->state_name . ' ' . $item->country->country_name . ' ' . $item->item_postal_code;
            $item->save();
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
            $table->dropColumn('item_location_str');
        });
    }
}

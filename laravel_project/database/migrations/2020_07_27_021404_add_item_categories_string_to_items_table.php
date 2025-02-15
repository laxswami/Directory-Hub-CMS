<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemCategoriesStringToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // store the copy of item categories value as string
            // from category_item table and categories table. Because
            // we want to search listing categories in one text box of
            // search bar.
            $table->longText('item_categories_string');
        });

        $existing_items = DB::table('items')->get();

        foreach($existing_items as $key_1 => $existing_item)
        {
            $item_categories = DB::table('category_item')
                ->where('item_id', $existing_item->id)
                ->get();

            $categories_string = "";
            foreach($item_categories as $key_2 => $item_category)
            {
                $category = DB::table('categories')
                    ->where('id', $item_category->category_id)
                    ->first();

                $categories_string .= $category->category_name . " ";
            }

            DB::table('items')->where('id',$existing_item->id)->update(array(
                'item_categories_string' => $categories_string,
            ));
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
            $table->dropColumn('item_categories_string');
        });
    }
}

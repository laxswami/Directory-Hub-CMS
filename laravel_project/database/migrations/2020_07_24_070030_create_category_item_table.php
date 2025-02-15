<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_item', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('category_id');
            $table->integer('item_id');

            $table->timestamps();
        });

        /**
         * Move existing data from items to category_item
         */
        $existing_items = DB::table('items')->get();

        foreach($existing_items as $key => $existing_item)
        {
            DB::table('category_item')->insert([
                [
                    'category_id' => $existing_item->category_id,
                    'item_id' => $existing_item->id,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ]
            ]);
        }

        Schema::table('items', function (Blueprint $table) {
            $table->integer('category_id')->nullable()->comment('ABANDONED')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_item');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemTypeToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('item_type')->default(1)->comment('1: regular item 2:online item');

            $table->integer('city_id')->nullable()->change();
            $table->integer('state_id')->nullable()->change();
            $table->integer('country_id')->nullable()->change();

            $table->string('item_lat')->nullable()->change();
            $table->string('item_lng')->nullable()->change();

            $table->string('item_postal_code')->nullable()->change();
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
            $table->dropColumn('item_type');

            $table->integer('city_id')->nullable(false)->change();
            $table->integer('state_id')->nullable(false)->change();
            $table->integer('country_id')->nullable(false)->change();

            $table->string('item_lat')->nullable(false)->change();
            $table->string('item_lng')->nullable(false)->change();

            $table->string('item_postal_code')->nullable(false)->change();
        });
    }
}

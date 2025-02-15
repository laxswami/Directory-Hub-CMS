<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('category_id');
            $table->integer('item_status')->default(1)->comment('1:submitted 2:published 3:suspended');
            $table->integer('item_featured')->default(0)->comment('0/1');
            $table->integer('item_featured_by_admin')->default(0)->comment('0/1');
            $table->string('item_title');
            $table->string('item_slug');
            $table->text('item_description');
            $table->string('item_image')->nullable();
            $table->string('item_address')->nullable();

            $table->integer('item_address_hide')->default(0)->comment('0: not hide 1:hide');

            $table->integer('city_id');
            $table->integer('state_id');
            $table->integer('country_id');

            $table->string('item_postal_code');

            $table->integer('item_price')->nullable();
            $table->string('item_website')->nullable();
            $table->string('item_phone')->nullable();

            $table->string('item_lat');
            $table->string('item_lng');
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
        Schema::dropIfExists('items');
    }
}

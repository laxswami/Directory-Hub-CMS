<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('user_id');
            $table->integer('product_status')->default(1)->comment('1:pending 2:approved 3:suspend');
            $table->string('product_name');
            $table->text('product_description');
            $table->decimal('product_price', 12, 2)->nullable();
            $table->string('product_slug');
            $table->string('product_image_small')->nullable();
            $table->string('product_image_medium')->nullable();
            $table->string('product_image_large')->nullable();

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
        Schema::dropIfExists('products');
    }
}

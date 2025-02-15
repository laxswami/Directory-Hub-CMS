<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemHourExceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_hour_exceptions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('item_id');
            $table->date('item_hour_exception_date');
            $table->time('item_hour_exception_open_time')->nullable();
            $table->time('item_hour_exception_close_time')->nullable();

            $table->timestamps();

            $table->index('item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_hour_exceptions');
    }
}

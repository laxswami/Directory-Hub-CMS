<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_hours', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('item_id');
            $table->integer('item_hour_day_of_week')->comment('1-7:mon-sun');
            $table->time('item_hour_open_time');
            $table->time('item_hour_close_time');

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
        Schema::dropIfExists('item_hours');
    }
}

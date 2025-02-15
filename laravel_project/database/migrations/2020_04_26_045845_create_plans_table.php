<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('plan_type')->comment('1:free 2:paid 3:admin_plan');
            $table->string('plan_name');
            $table->integer('plan_max_featured_listing')->nullable()->comment('unlimited listing if null');
            $table->text('plan_features')->nullable();
            $table->integer('plan_period')->comment('1:lifetime 2:monthly 3:quarterly 4:yearly');
            $table->decimal('plan_price', 5, 2);
            $table->integer('plan_status')->comment('1:enabled 0:disabled');

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
        Schema::dropIfExists('plans');
    }
}

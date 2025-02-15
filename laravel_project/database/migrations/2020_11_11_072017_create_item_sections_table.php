<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_sections', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('item_id');
            $table->string('item_section_title');
            $table->integer('item_section_position');
            $table->integer('item_section_order');
            $table->integer('item_section_status')->default(1)->comment('1:draft, 2:published');

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
        Schema::dropIfExists('item_sections');
    }
}

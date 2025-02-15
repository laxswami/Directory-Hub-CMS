<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_leads', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('item_id');
            $table->string('item_lead_name');
            $table->string('item_lead_email');
            $table->string('item_lead_phone')->nullable();
            $table->string('item_lead_subject')->nullable();
            $table->text('item_lead_message')->nullable();

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
        Schema::dropIfExists('item_leads');
    }
}

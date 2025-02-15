<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_claims', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('user_id');
            $table->integer('item_id');
            $table->string('item_claim_full_name');
            $table->string('item_claim_phone')->nullable();
            $table->string('item_claim_email')->nullable();
            $table->text('item_claim_additional_proof')->nullable();
            $table->string('item_claim_additional_upload')->nullable();
            $table->integer('item_claim_status')->nullable()->comment('1:claim requested, 2:claim disapprove, 3:claim approve');
            $table->text('item_claim_reply')->nullable();

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
        Schema::dropIfExists('item_claims');
    }
}

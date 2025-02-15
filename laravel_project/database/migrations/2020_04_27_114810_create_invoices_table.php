<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('subscription_id');
            $table->string('invoice_num');
            $table->string('invoice_item_title');
            $table->text('invoice_item_description');
            $table->decimal('invoice_amount', 5, 2);
            $table->string('invoice_status');
            $table->string('subscription_paypal_profile_id');

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
        Schema::dropIfExists('invoices');
    }
}

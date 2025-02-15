<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRazorpayToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoice_razorpay_payment_id')->nullable();
            //$table->string('invoice_razorpay_order_id')->nullable();
            $table->string('invoice_razorpay_signature')->nullable();

            $table->string('invoice_pay_method')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('invoice_pay_method');

            $table->dropColumn('invoice_razorpay_signature');
            //$table->dropColumn('invoice_razorpay_order_id');
            $table->dropColumn('invoice_razorpay_payment_id');
        });
    }
}

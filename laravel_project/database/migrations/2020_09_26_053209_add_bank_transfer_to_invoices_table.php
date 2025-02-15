<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankTransferToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {

            $table->string('invoice_bank_transfer_bank_name')->nullable();
            $table->text('invoice_bank_transfer_detail')->nullable();
            $table->integer('invoice_bank_transfer_future_plan_id')->nullable();

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

            $table->dropColumn('invoice_bank_transfer_future_plan_id');
            $table->dropColumn('invoice_bank_transfer_detail');
            $table->dropColumn('invoice_bank_transfer_bank_name');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingBankTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_bank_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('setting_bank_transfer_bank_name');
            $table->text('setting_bank_transfer_bank_account_info')->nullable();
            $table->integer('setting_bank_transfer_status')->default(0)->comment('0:disable, 1:enable');

            $table->timestamps();
        });

        DB::table('setting_bank_transfers')->insert([
            [
                'setting_bank_transfer_bank_name' => 'Bank of America',
                'setting_bank_transfer_bank_account_info' => 'Bank of America Account #: 8897 6546 8990 5433',
                'setting_bank_transfer_status' => \App\Setting::SITE_PAYMENT_BANK_TRANSFER_DISABLE,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_bank_transfers');
    }
}

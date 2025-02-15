<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('setting_product_max_gallery_photos')->default(6);
            $table->integer('setting_product_auto_approval_enable')->default(0)->comment('0:disable 1:enable');
            $table->string('setting_product_currency_symbol')->default('$');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('setting_product_currency_symbol');
            $table->dropColumn('setting_product_auto_approval_enable');
            $table->dropColumn('setting_product_max_gallery_photos');
        });
    }
}

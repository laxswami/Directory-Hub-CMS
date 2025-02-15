<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWhatsappInstagramToImportItemDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_item_data', function (Blueprint $table) {
            $table->string('import_item_data_item_social_whatsapp')->nullable();
            $table->string('import_item_data_item_social_instagram')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_item_data', function (Blueprint $table) {
            $table->dropColumn('import_item_data_item_social_instagram');
            $table->dropColumn('import_item_data_item_social_whatsapp');
        });
    }
}

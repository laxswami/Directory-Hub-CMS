<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('setting_id');
            $table->integer('setting_item_max_gallery_photos')->default(12);
            $table->integer('setting_item_auto_approval_enable')->default(0)->comment('0:disable 1:enable');

            $table->timestamps();
        });

        $settings_items = new \App\SettingItem(array(
            'setting_id' => 1,
            'setting_item_max_gallery_photos' => 12,
            'setting_item_auto_approval_enable' => \App\SettingItem::SITE_ITEM_AUTO_APPROVAL_DISABLED,
        ));
        $settings_items->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings_items');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStateCitySitemapToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {

            $table->integer('setting_site_sitemap_state_enable')->default(1)->comment('0:disable 1:enable');
            $table->string('setting_site_sitemap_state_frequency')->default('weekly')->comment('1:always 2:hourly 3:daily 4:weekly 5:monthly 6:yearly 7:never');
            $table->string('setting_site_sitemap_state_format')->default('xml')->comment('1:xml 2:html 3:txt 4:ror-rss 5:ror-rdf');
            $table->integer('setting_site_sitemap_state_include_to_index')->default(1)->comment('0:not include 1:include');

            $table->integer('setting_site_sitemap_city_enable')->default(1)->comment('0:disable 1:enable');
            $table->string('setting_site_sitemap_city_frequency')->default('weekly')->comment('1:always 2:hourly 3:daily 4:weekly 5:monthly 6:yearly 7:never');
            $table->string('setting_site_sitemap_city_format')->default('xml')->comment('1:xml 2:html 3:txt 4:ror-rss 5:ror-rdf');
            $table->integer('setting_site_sitemap_city_include_to_index')->default(1)->comment('0:not include 1:include');
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
            $table->dropColumn('setting_site_sitemap_city_include_to_index');
            $table->dropColumn('setting_site_sitemap_city_format');
            $table->dropColumn('setting_site_sitemap_city_frequency');
            $table->dropColumn('setting_site_sitemap_city_enable');
            $table->dropColumn('setting_site_sitemap_state_include_to_index');
            $table->dropColumn('setting_site_sitemap_state_format');
            $table->dropColumn('setting_site_sitemap_state_frequency');
            $table->dropColumn('setting_site_sitemap_state_enable');
        });
    }
}

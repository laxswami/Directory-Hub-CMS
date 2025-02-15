<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSitemapToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('setting_site_sitemap_index_enable')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_site_sitemap_show_in_footer')->default(1)->comment('0:not show 1:show');

            $table->integer('setting_site_sitemap_page_enable')->default(1)->comment('0:disable 1:enable');
            $table->string('setting_site_sitemap_page_frequency')->default('weekly')->comment('1:always 2:hourly 3:daily 4:weekly 5:monthly 6:yearly 7:never');
            $table->string('setting_site_sitemap_page_format')->default('xml')->comment('1:xml 2:html 3:txt 4:ror-rss 5:ror-rdf');
            $table->integer('setting_site_sitemap_page_include_to_index')->default(1)->comment('0:not include 1:include');

            $table->integer('setting_site_sitemap_category_enable')->default(1)->comment('0:disable 1:enable');
            $table->string('setting_site_sitemap_category_frequency')->default('weekly')->comment('1:always 2:hourly 3:daily 4:weekly 5:monthly 6:yearly 7:never');
            $table->string('setting_site_sitemap_category_format')->default('xml')->comment('1:xml 2:html 3:txt 4:ror-rss 5:ror-rdf');
            $table->integer('setting_site_sitemap_category_include_to_index')->default(1)->comment('0:not include 1:include');

            $table->integer('setting_site_sitemap_listing_enable')->default(1)->comment('0:disable 1:enable');
            $table->string('setting_site_sitemap_listing_frequency')->default('weekly')->comment('1:always 2:hourly 3:daily 4:weekly 5:monthly 6:yearly 7:never');
            $table->string('setting_site_sitemap_listing_format')->default('xml')->comment('1:xml 2:html 3:txt 4:ror-rss 5:ror-rdf');
            $table->integer('setting_site_sitemap_listing_include_to_index')->default(1)->comment('0:not include 1:include');

            $table->integer('setting_site_sitemap_post_enable')->default(1)->comment('0:disable 1:enable');
            $table->string('setting_site_sitemap_post_frequency')->default('weekly')->comment('1:always 2:hourly 3:daily 4:weekly 5:monthly 6:yearly 7:never');
            $table->string('setting_site_sitemap_post_format')->default('xml')->comment('1:xml 2:html 3:txt 4:ror-rss 5:ror-rdf');
            $table->integer('setting_site_sitemap_post_include_to_index')->default(1)->comment('0:not include 1:include');

            $table->integer('setting_site_sitemap_tag_enable')->default(1)->comment('0:disable 1:enable');
            $table->string('setting_site_sitemap_tag_frequency')->default('weekly')->comment('1:always 2:hourly 3:daily 4:weekly 5:monthly 6:yearly 7:never');
            $table->string('setting_site_sitemap_tag_format')->default('xml')->comment('1:xml 2:html 3:txt 4:ror-rss 5:ror-rdf');
            $table->integer('setting_site_sitemap_tag_include_to_index')->default(1)->comment('0:not include 1:include');

            $table->integer('setting_site_sitemap_topic_enable')->default(1)->comment('0:disable 1:enable');
            $table->string('setting_site_sitemap_topic_frequency')->default('weekly')->comment('1:always 2:hourly 3:daily 4:weekly 5:monthly 6:yearly 7:never');
            $table->string('setting_site_sitemap_topic_format')->default('xml')->comment('1:xml 2:html 3:txt 4:ror-rss 5:ror-rdf');
            $table->integer('setting_site_sitemap_topic_include_to_index')->default(1)->comment('0:not include 1:include');
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
            $table->dropColumn('setting_site_sitemap_index_enable');
            $table->dropColumn('setting_site_sitemap_show_in_footer');

            $table->dropColumn('setting_site_sitemap_page_enable');
            $table->dropColumn('setting_site_sitemap_page_frequency');
            $table->dropColumn('setting_site_sitemap_page_format');
            $table->dropColumn('setting_site_sitemap_page_include_to_index');

            $table->dropColumn('setting_site_sitemap_category_enable');
            $table->dropColumn('setting_site_sitemap_category_frequency');
            $table->dropColumn('setting_site_sitemap_category_format');
            $table->dropColumn('setting_site_sitemap_category_include_to_index');

            $table->dropColumn('setting_site_sitemap_listing_enable');
            $table->dropColumn('setting_site_sitemap_listing_frequency');
            $table->dropColumn('setting_site_sitemap_listing_format');
            $table->dropColumn('setting_site_sitemap_listing_include_to_index');

            $table->dropColumn('setting_site_sitemap_post_enable');
            $table->dropColumn('setting_site_sitemap_post_frequency');
            $table->dropColumn('setting_site_sitemap_post_format');
            $table->dropColumn('setting_site_sitemap_post_include_to_index');

            $table->dropColumn('setting_site_sitemap_tag_enable');
            $table->dropColumn('setting_site_sitemap_tag_frequency');
            $table->dropColumn('setting_site_sitemap_tag_format');
            $table->dropColumn('setting_site_sitemap_tag_include_to_index');

            $table->dropColumn('setting_site_sitemap_topic_enable');
            $table->dropColumn('setting_site_sitemap_topic_frequency');
            $table->dropColumn('setting_site_sitemap_topic_format');
            $table->dropColumn('setting_site_sitemap_topic_include_to_index');
        });
    }
}

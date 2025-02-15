<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('advertisement_name');
            $table->integer('advertisement_status')->default(0)->comment('0: disable 1: enable');

            /**
             * advertisement_place indicate what type of pages to put the ad.
             *
             * AD_PLACE_LISTING_RESULTS_PAGES - 1
             * AD_PLACE_LISTING_SEARCH_PAGE - 2
             * AD_PLACE_BUSINESS_LISTING_PAGE - 3
             * AD_PLACE_BLOG_POSTS_PAGES - 4
             * AD_PLACE_BLOG_TOPIC_PAGES - 5
             * AD_PLACE_BLOG_TAG_PAGES - 6
             * AD_PLACE_SINGLE_POST_PAGE - 7
             *
             */
            $table->integer('advertisement_place')->default(1)->comment('1:listing results pages 2: listing search page 3: business listing page 4: blog posts pages 5: blog topic pages 6: blog tag pages 7: single post page');
            $table->text('advertisement_code');

            /**
             * The advertisement_position indicate where to put the ad according the value of advertisement_place
             *
             * advertisement_place : 1 - listing result pages
             *
             * AD_POSITION_BEFORE_BREADCRUMB - 1
             * AD_POSITION_AFTER_BREADCRUMB - 18
             * AD_POSITION_BEFORE_CONTENT - 2
             * AD_POSITION_AFTER_CONTENT - 3
             * AD_POSITION_SIDEBAR_BEFORE_CONTENT - 4
             * AD_POSITION_SIDEBAR_AFTER_CONTENT - 5
             *
             * advertisement_place : 2 - listing search page
             *
             * AD_POSITION_BEFORE_BREADCRUMB - 1
             * AD_POSITION_AFTER_BREADCRUMB - 18
             * AD_POSITION_BEFORE_CONTENT - 2
             * AD_POSITION_AFTER_CONTENT - 3
             *
             * advertisement_place : 3 - business listing page
             *
             * AD_POSITION_BEFORE_BREADCRUMB - 1
             * AD_POSITION_AFTER_BREADCRUMB - 18
             * AD_POSITION_BEFORE_GALLERY - 6
             * AD_POSITION_BEFORE_DESCRIPTION - 7
             * AD_POSITION_BEFORE_LOCATION - 8
             * AD_POSITION_BEFORE_FEATURES - 9
             * AD_POSITION_BEFORE_REVIEWS - 10
             * AD_POSITION_BEFORE_COMMENTS - 11
             * AD_POSITION_BEFORE_SHARE - 12
             * AD_POSITION_AFTER_SHARE - 13
             * AD_POSITION_SIDEBAR_BEFORE_CONTENT - 4
             * AD_POSITION_SIDEBAR_AFTER_CONTENT - 5
             *
             * advertisement_place : 4 - blog posts pages
             * advertisement_place : 5 - blog topic pages
             * advertisement_place : 6 - blog tag pages
             *
             * AD_POSITION_BEFORE_BREADCRUMB - 1
             * AD_POSITION_AFTER_BREADCRUMB - 18
             * AD_POSITION_BEFORE_CONTENT - 2
             * AD_POSITION_AFTER_CONTENT - 3
             * AD_POSITION_SIDEBAR_BEFORE_CONTENT - 4
             * AD_POSITION_SIDEBAR_AFTER_CONTENT - 5
             *
             * advertisement_place : 7 - single post page
             *
             * AD_POSITION_BEFORE_BREADCRUMB - 1
             * AD_POSITION_AFTER_BREADCRUMB - 18
             * AD_POSITION_BEFORE_FEATURE_IMAGE - 14
             * AD_POSITION_BEFORE_TITLE - 15
             * AD_POSITION_BEFORE_POST_CONTENT - 16
             * AD_POSITION_AFTER_POST_CONTENT - 17
             * AD_POSITION_BEFORE_COMMENTS - 11
             * AD_POSITION_BEFORE_SHARE - 12
             * AD_POSITION_AFTER_SHARE - 13
             * AD_POSITION_SIDEBAR_BEFORE_CONTENT - 4
             * AD_POSITION_SIDEBAR_AFTER_CONTENT - 5
             *
             */
            $table->integer('advertisement_position')->default(1)->comment('0: disable 1: enable');

            $table->integer('advertisement_alignment')->default(1)->comment('1: left 2: center 3: right');

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
        Schema::dropIfExists('advertisements');
    }
}

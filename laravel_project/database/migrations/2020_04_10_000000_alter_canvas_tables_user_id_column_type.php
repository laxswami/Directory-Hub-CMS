<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCanvasTablesUserIdColumnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('canvas_user_meta', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->charset(null)->change();
        });

        Schema::table('canvas_posts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->charset(null)->change();
        });

        Schema::table('canvas_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->charset(null)->change();
        });

        Schema::table('canvas_topics', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->charset(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('canvas_user_meta', function (Blueprint $table) {
            $table->string('user_id')->change();
        });

        Schema::table('canvas_posts', function (Blueprint $table) {
            $table->string('user_id')->change();
        });

        Schema::table('canvas_tags', function (Blueprint $table) {
            $table->string('user_id')->change();
        });

        Schema::table('canvas_topics', function (Blueprint $table) {
            $table->string('user_id')->change();
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewImageGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_image_galleries', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('review_id');
            $table->string('review_image_gallery_name');
            $table->string('review_image_gallery_thumb_name');
            $table->string('review_image_gallery_size')->nullable();

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
        Schema::dropIfExists('review_image_galleries');
    }
}

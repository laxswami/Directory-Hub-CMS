<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCategoryCustomFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_custom_field', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('category_id');
            $table->integer('custom_field_id');

            $table->timestamps();
        });

        /**
         * Move existing data from custom_fields to category_custom_field
         */
        $existing_custom_fields = DB::table('custom_fields')->get();

        foreach($existing_custom_fields as $key => $existing_custom_field)
        {
            DB::table('category_custom_field')->insert([
                [
                    'category_id' => $existing_custom_field->category_id,
                    'custom_field_id' => $existing_custom_field->id,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ]
            ]);
        }

        Schema::table('custom_fields', function (Blueprint $table) {
            $table->integer('category_id')->nullable()->comment('ABANDONED')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_custom_field');
    }
}

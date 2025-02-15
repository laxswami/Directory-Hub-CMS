<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportCsvDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_csv_data', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('import_csv_data_filename');
            $table->longText('import_csv_data_sample');
            $table->integer('import_csv_data_skip_first_row')->default(1);
            $table->integer('import_csv_data_total_rows');
            $table->integer('import_csv_data_parsed_rows')->default(0);
            $table->integer('import_csv_data_parse_status')->default(1)->comment('1:not parsed 2:partial parsed 3:all parsed');
            $table->integer('import_csv_data_for_model')->default(1)->comment('1:listing 2:category 3:product');

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
        Schema::dropIfExists('import_csv_data');
    }
}

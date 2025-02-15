<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportCsvData extends Model
{
    const IMPORT_CSV_SKIP_FIRST_ROW_YES = 1;
    const IMPORT_CSV_SKIP_FIRST_ROW_NO = 0;

    const IMPORT_CSV_STATUS_NOT_PARSED = 1;
    const IMPORT_CSV_STATUS_PARTIAL_PARSED = 2;
    const IMPORT_CSV_STATUS_ALL_PARSED = 3;

    const IMPORT_CSV_FOR_MODEL_LISTING = 1;
    const IMPORT_CSV_FOR_MODEL_CATEGORY = 2;
    const IMPORT_CSV_FOR_MODEL_PRODUCT = 3;

    const URL_PHP_TIMEZONE = 'https://www.php.net/manual/en/timezones.php';
    const URL_DOWNLOAD_SAMPLE_CSV = 'https://alphastir.com/wp-content/uploads/2022/06/directory-hub-importer-sample-v38.csv';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'import_csv_data';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'import_csv_data_filename',
        'import_csv_data_sample',
        'import_csv_data_skip_first_row',
        'import_csv_data_total_rows',
        'import_csv_data_parsed_rows',
        'import_csv_data_parse_status',
        'import_csv_data_for_model',
    ];
}

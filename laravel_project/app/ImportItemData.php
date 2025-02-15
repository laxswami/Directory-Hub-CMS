<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportItemData extends Model
{
    const PROCESS_STATUS_NOT_PROCESSED = 1;
    const PROCESS_STATUS_PROCESSED_SUCCESS = 2;
    const PROCESS_STATUS_PROCESSED_ERROR = 3;

    const SOURCE_CSV = 1;
    const SOURCE_GOOGLE_PLACE = 2;

    const ORDER_BY_ITEM_NEWEST_PROCESSED = 1;
    const ORDER_BY_ITEM_OLDEST_PROCESSED = 2;
    const ORDER_BY_ITEM_NEWEST_PARSED = 3;
    const ORDER_BY_ITEM_OLDEST_PARSED = 4;
    const ORDER_BY_ITEM_TITLE_A_Z = 5;
    const ORDER_BY_ITEM_TITLE_Z_A = 6;
    const ORDER_BY_ITEM_CITY_A_Z = 7;
    const ORDER_BY_ITEM_CITY_Z_A = 8;
    const ORDER_BY_ITEM_STATE_A_Z = 9;
    const ORDER_BY_ITEM_STATE_Z_A = 10;
    const ORDER_BY_ITEM_COUNTRY_A_Z = 11;
    const ORDER_BY_ITEM_COUNTRY_Z_A = 12;

    const COUNT_PER_PAGE_10 = 10;
    const COUNT_PER_PAGE_25 = 25;
    const COUNT_PER_PAGE_50 = 50;
    const COUNT_PER_PAGE_100 = 100;
    const COUNT_PER_PAGE_250 = 250;
    const COUNT_PER_PAGE_500 = 500;
    const COUNT_PER_PAGE_1000 = 1000;

    const IMPORT_RANDOM_USER = 'random_user';

    const DATA_COLUMNS_DO_NOT_PARSE = 'DO NOT PARSE';

    const DATA_COLUMNS = array(
        'import_item_data_item_title',
        'import_item_data_item_address',
        'import_item_data_city',
        'import_item_data_state',
        'import_item_data_country',
        'import_item_data_item_lat',
        'import_item_data_item_lng',
        'import_item_data_item_postal_code',
        'import_item_data_item_description',
        'import_item_data_item_phone',
        'import_item_data_item_website',
        'import_item_data_item_social_facebook',
        'import_item_data_item_social_twitter',
        'import_item_data_item_social_linkedin',
        'import_item_data_item_social_instagram',
        'import_item_data_item_social_whatsapp',
        'import_item_data_item_youtube_id',
        'import_item_data_item_image',
        'import_item_data_item_image_galleries',
        'import_item_data_item_hour_time_zone',
        'import_item_data_item_hour_show_hours',
        'import_item_data_item_hours',
        'import_item_data_item_image_hour_exceptions',
    );


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'import_item_data';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'import_item_data_markup',
        'import_item_data_item_title',
        'import_item_data_item_slug',
        'import_item_data_item_address',
        'import_item_data_city',
        'import_item_data_state',
        'import_item_data_country',
        'import_item_data_item_lat',
        'import_item_data_item_lng',
        'import_item_data_item_postal_code',
        'import_item_data_item_description',
        'import_item_data_item_phone',
        'import_item_data_item_website',
        'import_item_data_item_social_facebook',
        'import_item_data_item_social_twitter',
        'import_item_data_item_social_linkedin',
        'import_item_data_item_social_whatsapp',
        'import_item_data_item_social_instagram',
        'import_item_data_item_youtube_id',
        'import_item_data_process_status',
        'import_item_data_item_id',
        'import_item_data_source',
        'import_item_data_process_error_log',
        'import_item_data_item_image',
        'import_item_data_item_image_galleries',
        'import_item_data_item_hour_time_zone',
        'import_item_data_item_hour_show_hours',
        'import_item_data_item_hours',
        'import_item_data_item_image_hour_exceptions',
    ];

    public function importItemFeatureData()
    {
        return $this->hasMany('App\ImportItemFeatureData')->orderBy('id');
    }

    public function deleteImportItemData()
    {
        // #1 - delete features
        $this->importItemFeatureData()->delete();

        // #2 - delete
        $this->delete();
    }
}

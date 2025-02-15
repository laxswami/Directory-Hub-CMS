<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportItemFeatureData extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'import_item_feature_data';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'import_item_data_id',
        'import_item_feature_data_custom_field_id',
        'import_item_feature_data_item_feature_value',
    ];

    public function importItemData()
    {
        return $this->belongsTo('App\ImportItemData');
    }
}

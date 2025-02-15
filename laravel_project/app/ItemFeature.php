<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class ItemFeature extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id', 'custom_field_id', 'item_feature_value'
    ];

    /**
     * Get the custom field that owns the item.
     */
    public function customField()
    {
        return $this->belongsTo('App\CustomField');
    }

    /**
     * Get the item that owns the item.
     */
    public function item()
    {
        return $this->belongsTo('App\Item');
    }

}

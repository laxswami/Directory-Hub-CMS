<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemSectionCollection extends Model
{
    const COLLECTIBLE_TYPE_PRODUCT = 'App\Product';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_section_collections';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_section_id',
        'item_section_collection_order',
        'item_section_collection_collectible_type',
        'item_section_collection_collectible_id',
    ];

    /**
     * Get the item section that owns the item section collection
     * @return BelongsTo
     */
    public function itemSection()
    {
        return $this->belongsTo('App\ItemSection');
    }
}

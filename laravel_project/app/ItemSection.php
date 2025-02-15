<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemSection extends Model
{
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;

    const POSITION_AFTER_BREADCRUMB = 1;
    const POSITION_AFTER_GALLERY = 2;
    const POSITION_AFTER_DESCRIPTION = 3;
    const POSITION_AFTER_LOCATION_MAP = 4;
    const POSITION_AFTER_FEATURES = 5;
    const POSITION_AFTER_REVIEWS = 6;
    const POSITION_AFTER_COMMENTS = 7;
    const POSITION_AFTER_SHARE = 8;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_sections';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'item_section_title',
        'item_section_position',
        'item_section_order',
        'item_section_status',
    ];

    /**
     * Get the item that owns the item section
     * @return BelongsTo
     */
    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    /**
     * Get the item section collections of this item section
     * @return HasMany
     */
    public function itemSectionCollections()
    {
        return $this->hasMany('App\ItemSectionCollection');
    }
}

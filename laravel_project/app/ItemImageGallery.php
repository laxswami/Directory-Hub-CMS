<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemImageGallery extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'item_image_gallery_name',
        'item_image_gallery_thumb_name',
        'item_image_gallery_size',
    ];

    /**
     * Get the item that owns the gallery image.
     */
    public function item()
    {
        return $this->belongsTo('App\Item');
    }
}

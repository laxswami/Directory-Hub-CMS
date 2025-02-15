<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImageGallery extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_image_galleries';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'product_image_gallery_name',
        'product_image_gallery_thumb_name',
    ];

    /**
     * Get the item that owns the gallery image.
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}

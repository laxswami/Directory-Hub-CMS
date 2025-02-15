<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_SUSPEND = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product_status',
        'product_name',
        'product_description',
        'product_price',
        'product_slug',
        'product_image_small',
        'product_image_medium',
        'product_image_large',
    ];

    /**
     * Get the user that owns the product.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function productGalleries()
    {
        return $this->hasMany('App\ProductImageGallery');
    }

    public function productFeatures()
    {
        return $this->hasMany('App\ProductFeature')
            ->orderBy('product_feature_order');
    }

    public function hasAssignedToListing()
    {
        // Check if the product currently used in item_section_collections table
        $product_in_use = ItemSectionCollection::where('item_section_collection_collectible_type', ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
            ->where('item_section_collection_collectible_id', $this->id)->count();

        return $product_in_use > 0 ? true : false;
    }

    public function deleteProductFeatureImage()
    {
        if(!empty($this->product_image_small))
        {
            if(Storage::disk('public')->exists('product/' . $this->product_image_small)){
                Storage::disk('public')->delete('product/' . $this->product_image_small);
            }

            $this->product_image_small = null;
        }
        if(!empty($this->product_image_medium))
        {
            if(Storage::disk('public')->exists('product/' . $this->product_image_medium)){
                Storage::disk('public')->delete('product/' . $this->product_image_medium);
            }

            $this->product_image_medium = null;
        }
        if(!empty($this->product_image_large))
        {
            if(Storage::disk('public')->exists('product/' . $this->product_image_large)){
                Storage::disk('public')->delete('product/' . $this->product_image_large);
            }

            $this->product_image_large = null;
        }

        $this->save();
    }

    public function deleteProduct()
    {
        // #1 - delete product galleries, and image files.
        $product_image_gallery = $this->productGalleries()->get();

        foreach($product_image_gallery as $product_image_gallery_key => $gallery)
        {
            if(!empty($gallery->product_image_gallery_name))
            {
                if(Storage::disk('public')->exists('product/gallery/' . $gallery->product_image_gallery_name)){
                    Storage::disk('public')->delete('product/gallery/' . $gallery->product_image_gallery_name);
                }
            }

            if(!empty($gallery->product_image_gallery_thumb_name))
            {
                if(Storage::disk('public')->exists('product/gallery/' . $gallery->product_image_gallery_thumb_name)){
                    Storage::disk('public')->delete('product/gallery/' . $gallery->product_image_gallery_thumb_name);
                }
            }

            $gallery->delete();
        }

        // #2 - delete product features.
        $product_features = $this->productFeatures()->get();

        foreach($product_features as $product_features_key => $product_feature)
        {
            $product_feature->delete();
        }

        // #3 - delete product feature image files.
        $this->deleteProductFeatureImage();

        // #4 - delete the product record.
        $this->delete();
    }
}

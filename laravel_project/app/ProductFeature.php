<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_features';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'attribute_id',
        'product_feature_value',
        'product_feature_order',
    ];

    public function attribute()
    {
        return $this->belongsTo('App\Attribute');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}

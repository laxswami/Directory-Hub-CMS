<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    const TYPE_TEXT = 1;
    const TYPE_SELECT = 2;
    const TYPE_MULTI_SELECT = 3;
    const TYPE_LINK = 4;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attributes';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'attribute_name',
        'attribute_type',
        'attribute_seed_value',
    ];

    /**
     * Get the user that owns the attribute.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function productFeatures()
    {
        return $this->hasMany('App\ProductFeature');
    }

    public function deleteAttribute()
    {
        $this->delete();
    }
}

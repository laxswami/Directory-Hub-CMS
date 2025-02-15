<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'state_id', 'city_name', 'city_state', 'city_slug', 'city_lat', 'city_lng'
    ];

    /**
     * Get the state that owns the city.
     */
    public function state()
    {
        return $this->belongsTo('App\State');
    }

    /**
     * Get the items for the city.
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function deleteCity()
    {
        // #1 - delete all items which in this state
        $items = $this->items()->get();
        foreach($items as $items_key => $item)
        {
            $item->deleteItem();
        }

        // #2 - delete the city record
        $this->delete();
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id', 'state_name', 'state_abbr', 'state_slug', 'state_country_abbr'
    ];

    /**
     * Get the cities for the state.
     */
    public function cities()
    {
        return $this->hasMany('App\City');
    }

    /**
     * Get the country that owns the state.
     */
    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    /**
     * Get the items for the state.
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function deleteState()
    {
        // #1 - delete all items which in this state
        $items = $this->items()->get();
        foreach($items as $items_key => $item)
        {
            $item->deleteItem();
        }

        // #2 - delete all cities of this state
        $this->cities()->delete();

        // #3 - delete the state record
        $this->delete();
    }
}

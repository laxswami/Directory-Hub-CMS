<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    const COUNTRY_STATUS_ENABLE = 1;
    const COUNTRY_STATUS_DISABLE = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_abbr',
        'country_name',
        'country_slug',
        'country_status',
    ];

    /**
     * Get the states for the country.
     */
    public function states()
    {
        return $this->hasMany('App\State');
    }

    /**
     * Get the items for the country.
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function deleteCountry()
    {
        // #1 - Set user_prefer_country_id=null in users table
        $users = User::where('user_prefer_country_id', $this->id)->get();
        foreach($users as $users_key => $user)
        {
            $user->user_prefer_country_id = null;
            $user->save();
        }

        // #2 - delete all items which in this country
        $items = $this->items()->get();
        foreach($items as $items_key => $item)
        {
            $item->deleteItem();
        }

        // #3 - delete all cities and states of this country
        $states = $this->states()->get();
        foreach($states as $states_key => $state)
        {
            $state->cities()->delete();
            $state->delete();
        }

        // #4 - delete the country record
        $this->delete();
    }
}

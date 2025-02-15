<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemHour extends Model
{
    const DAY_OF_WEEK_MONDAY = 1;
    const DAY_OF_WEEK_TUESDAY = 2;
    const DAY_OF_WEEK_WEDNESDAY = 3;
    const DAY_OF_WEEK_THURSDAY = 4;
    const DAY_OF_WEEK_FRIDAY = 5;
    const DAY_OF_WEEK_SATURDAY = 6;
    const DAY_OF_WEEK_SUNDAY = 7;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_hours';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'item_hour_day_of_week',
        'item_hour_open_time',
        'item_hour_close_time',
    ];

    public function item()
    {
        return $this->belongsTo('App\Item');
    }
}

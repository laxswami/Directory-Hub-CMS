<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemHourException extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_hour_exceptions';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'item_hour_exception_date',
        'item_hour_exception_open_time',
        'item_hour_exception_close_time',
    ];

    public function item()
    {
        return $this->belongsTo('App\Item');
    }
}

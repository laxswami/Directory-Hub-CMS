<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThreadItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'thread_item_rels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'thread_id', 'item_id',
    ];

    /**
     * Get the item.
     */
    public function item()
    {
        return $this->belongsTo('App\Item');
    }
}

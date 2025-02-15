<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemLead extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_leads';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'item_lead_name',
        'item_lead_email',
        'item_lead_phone',
        'item_lead_subject',
        'item_lead_message',
    ];

    /**
     * Get the item that owns the item lead.
     */
    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    public function deleteItemLead()
    {
        $this->delete();
    }
}

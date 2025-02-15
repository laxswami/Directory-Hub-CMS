<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingItem extends Model
{
    const SITE_ITEM_AUTO_APPROVAL_ENABLED = 1;
    const SITE_ITEM_AUTO_APPROVAL_DISABLED = 0;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings_items';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setting_id',
        'setting_item_max_gallery_photos',
        'setting_item_auto_approval_enable',
    ];

    /**
     * Get the user that owns the item.
     */
    public function setting()
    {
        return $this->belongsTo('App\Setting');
    }
}

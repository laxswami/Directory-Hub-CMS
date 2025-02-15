<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomField extends Model
{
    const TYPE_TEXT = 1;
    const TYPE_SELECT = 2;
    const TYPE_MULTI_SELECT = 3;
    const TYPE_LINK = 4;

    const CUSTOM_FIELDS_SORT_BY_NEWEST_CREATED = 1;
    const CUSTOM_FIELDS_SORT_BY_OLDEST_CREATED = 2;
    const CUSTOM_FIELDS_SORT_BY_NEWEST_UPDATED = 3;
    const CUSTOM_FIELDS_SORT_BY_OLDEST_UPDATED = 4;
    const CUSTOM_FIELDS_SORT_BY_CUSTOM_FIELD_NAME_A_Z = 5;
    const CUSTOM_FIELDS_SORT_BY_CUSTOM_FIELD_NAME_Z_A = 6;
    const CUSTOM_FIELDS_SORT_BY_MOST_RELEVANT = 7;

    const COUNT_PER_PAGE_10 = 10;
    const COUNT_PER_PAGE_25 = 25;
    const COUNT_PER_PAGE_50 = 50;
    const COUNT_PER_PAGE_100 = 100;
    const COUNT_PER_PAGE_250 = 250;
    const COUNT_PER_PAGE_500 = 500;
    const COUNT_PER_PAGE_1000 = 1000;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'custom_field_name', 'custom_field_type', 'custom_field_seed_value',
        'custom_field_icon_class', 'custom_field_order'
    ];

    /**
     * Get the category that owns the item.
     */
    public function allCategories()
    {
        return $this->belongsToMany('App\Category', 'category_custom_field')->withTimestamps();
    }

    /**
     * Get the item features for the custom field.
     */
    public function itemFeatures()
    {
        return $this->hasMany('App\ItemFeature');
    }

    public function isBelongToCategory(int $category_id)
    {
        return DB::table('category_custom_field')
            ->where('category_id', $category_id)
            ->where('custom_field_id', $this->id)
            ->get()
            ->count() > 0 ? true : false;
    }

    public function getDistinctCustomFieldsByCategories(array $category_ids)
    {
        $custom_field_ids = DB::table('category_custom_field')
            ->whereIn('category_id', $category_ids)
            ->distinct('custom_field_id')
            ->get();

        $select_custom_field = array();
        foreach($custom_field_ids as $key => $custom_field_id)
        {
            $select_custom_field[] = $custom_field_id->custom_field_id;
        }

        return CustomField::whereIn('id', $select_custom_field)
            ->orderBy('custom_field_order')
            ->orderBy('created_at')
            ->get();
    }

    public function deleteCustomField()
    {
        $this->itemFeatures()->delete();

        $this->allCategories()->sync([]);

        $this->delete();
    }
}

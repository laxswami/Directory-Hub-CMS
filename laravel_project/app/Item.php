<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Laravelista\Comments\Commentable;
use Nicolaslopezj\Searchable\SearchableTrait;
use Codebyray\ReviewRateable\Contracts\ReviewRateable;
use Codebyray\ReviewRateable\Traits\ReviewRateable as ReviewRateableTrait;
use Spatie\OpeningHours\OpeningHours;
use DateTime;

class Item extends Model implements ReviewRateable
{
    use Commentable, SearchableTrait, ReviewRateableTrait;

    const ITEM_SUBMITTED = 1;
    const ITEM_PUBLISHED = 2;
    const ITEM_SUSPENDED = 3;

    const ITEM_FEATURED = 1;
    const ITEM_NOT_FEATURED = 0;

    const ITEM_FEATURED_BY_ADMIN = 1;
    const ITEM_NOT_FEATURED_BY_ADMIN = 0;

    const ITEM_ADDR_HIDE = 1;
    const ITEM_ADDR_NOT_HIDE = 0;

    const ITEM_REVIEW_RECOMMEND_YES = 'Yes';
    const ITEM_REVIEW_RECOMMEND_NO = 'No';

    const ITEM_REVIEW_RATING_ONE = 1;
    const ITEM_REVIEW_RATING_TWO = 2;
    const ITEM_REVIEW_RATING_THREE = 3;
    const ITEM_REVIEW_RATING_FOUR = 4;
    const ITEM_REVIEW_RATING_FIVE = 5;

    const ITEM_REVIEW_APPROVED = 1;
    const ITEM_REVIEW_PENDING = 0;

    const ITEM_TOTAL_SHOW_CATEGORY = 4;
    const ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE = 2;

    const ITEM_RATING_SORT_BY_NEWEST = 1;
    const ITEM_RATING_SORT_BY_OLDEST = 2;
    const ITEM_RATING_SORT_BY_HIGHEST = 3;
    const ITEM_RATING_SORT_BY_LOWEST = 4;

    const ITEMS_SORT_BY_NEWEST_CREATED = 1;
    const ITEMS_SORT_BY_OLDEST_CREATED = 2;
    const ITEMS_SORT_BY_HIGHEST_RATING = 3;
    const ITEMS_SORT_BY_LOWEST_RATING = 4;
    const ITEMS_SORT_BY_NEWEST_UPDATED = 5;
    const ITEMS_SORT_BY_OLDEST_UPDATED = 6;
    const ITEMS_SORT_BY_NEARBY_FIRST = 7;
    const ITEMS_SORT_BY_MOST_RELEVANT = 8;

    const COUNT_PER_PAGE_10 = 10;
    const COUNT_PER_PAGE_25 = 25;
    const COUNT_PER_PAGE_50 = 50;
    const COUNT_PER_PAGE_100 = 100;
    const COUNT_PER_PAGE_250 = 250;
    const COUNT_PER_PAGE_500 = 500;
    const COUNT_PER_PAGE_1000 = 1000;

    const ITEM_TYPE_REGULAR = 1;
    const ITEM_TYPE_ONLINE = 2;

    const ITEM_NEARBY_SHOW_MAX = 4;
    const ITEM_SIMILAR_SHOW_MAX = 4;

    const ITEM_HOUR_SHOW = 1;
    const ITEM_HOUR_NOT_SHOW = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'item_status',
        'item_featured',
        'item_featured_by_admin',
        'item_title',
        'item_slug',
        'item_description',
        'item_image',
        'item_address',
        'item_address_hide',
        'city_id',
        'state_id',
        'country_id',
        'item_postal_code',
        'item_price',
        'item_lat',
        'item_lng',
        'item_phone',
        'item_website',
        'item_social_facebook',
        'item_social_twitter',
        'item_social_linkedin',
        'item_features_string',
        'item_image_medium',
        'item_image_small',
        'item_image_tiny',
        'item_categories_string',
        'item_image_blur',
        'item_youtube_id',
        'item_location_str',
        'item_type',
        'item_hour_time_zone',
        'item_hour_show_hours',
        'item_social_whatsapp',
        'item_social_instagram',
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'items.item_title' => 10,
            'items.item_categories_string' => 9,
            'items.item_location_str' => 8,
            'items.item_description' => 7,
            'items.item_features_string' => 6,
        ],
        'joins' => [
        ],
    ];

    /**
     * Get the categories that owns the item.
     */
    public function allCategories()
    {
        return $this->belongsToMany('App\Category', 'category_item')->withTimestamps();
    }

    public function getAllCategories($limit_rows=0, $parent_category=0)
    {
        $query_build = $this->allCategories();

        if($limit_rows > 0)
        {
            $query_build->limit($limit_rows);
        }

        if($parent_category > 0)
        {
            $parent_category = Category::findOrFail($parent_category);
            $children_categories_ids = array();
            $children_categories = collect();
            $parent_category->allChildren($parent_category, $children_categories);

            foreach($children_categories as $key => $children_category)
            {
                $children_categories_ids[] = $children_category->id;
            }

            if(count($children_categories_ids) > 0)
            {
                $query_build->whereIn('category_item.category_id', $children_categories_ids);
            }
        }

        $query_build->orderBy('category_parent_id');

        return $query_build->get();
    }

    /**
     * Get the item leads for the item.
     */
    public function itemLeads()
    {
        return $this->hasMany('App\ItemLead');
    }

    /**
     * Get the user that owns the item.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the gallery images for the item.
     */
    public function galleries()
    {
        return $this->hasMany('App\ItemImageGallery');
    }

    /**
     * Get the item sections for the item
     * @return HasMany
     */
    public function itemSections()
    {
        return $this->hasMany('App\ItemSection');
    }

    /**
     * Get the claims for the item
     * @return HasMany
     */
    public function claims()
    {
        return $this->hasMany('App\ItemClaim');
    }

    /**
     * Get the item features for the item.
     */
    public function features()
    {
        return $this->hasMany('App\ItemFeature')->orderBy('id');
    }

    /**
     * Get the item state that owns the item.
     */
    public function state()
    {
        return $this->belongsTo('App\State');
    }

    /**
     * Get the item city that owns the item.
     */
    public function city()
    {
        return $this->belongsTo('App\City');
    }

    /**
     * Get the item country that owns the item.
     */
    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    /**
     * Get the thread_item_rels table records for the item.
     */
    public function threadItems()
    {
        return $this->hasMany('App\ThreadItem');
    }

    public function itemHours()
    {
        return $this->hasMany('App\ItemHour');
    }

    public function itemHourExceptions()
    {
        return $this->hasMany('App\ItemHourException');
    }

    /**
     * Get all of the post's comments.
     */
    public function totalComments()
    {
        return DB::table('comments')
            ->where('commentable_type', 'App\Item')
            ->where('approved', 1)
            ->where('commentable_id', $this->id)
            ->count();
    }

    /**
     * Get all of users who saved this item
     */
    public function savedByUsers()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    public function hasReviewByIdAndUser($review_id, $user_id)
    {
        return DB::table('reviews')
            ->where('id', $review_id)
            ->where('author_id', $user_id)
            ->where('reviewrateable_id', $this->id)
            ->count();
    }

    public function hasReviewById($review_id)
    {
        return DB::table('reviews')
            ->where('id', $review_id)
            ->where('reviewrateable_id', $this->id)
            ->count();
    }

    public function getReviewById($review_id)
    {
        return DB::table('reviews')
            ->where('id', $review_id)
            ->where('reviewrateable_id', $this->id)
            ->first();
    }

    public function reviewedByUser($user_id)
    {
        return DB::table('reviews')
            ->where('author_id', $user_id)
            ->where('reviewrateable_id', $this->id)
            ->count();
    }

    public function getReviewByUser($user_id)
    {
        return DB::table('reviews')
            ->where('author_id', $user_id)
            ->where('reviewrateable_id', $this->id)
            ->first();
    }

    public function getAverageRating()
    {
        $average_rating_query = DB::table('reviews')
            ->selectRaw('ROUND(AVG(rating), 1) as average_rating')
            ->where('reviewrateable_id', $this->id)
            ->where('approved', self::ITEM_REVIEW_APPROVED)
            ->first();

        return floatval($average_rating_query->average_rating);
    }

    public function getCountRating()
    {
        return DB::table('reviews')
            ->where('approved', self::ITEM_REVIEW_APPROVED)
            ->where('reviewrateable_id', $this->id)
            ->count();
    }

    public function syncItemAverageRating()
    {
        if($this->getCountRating() > 0)
        {
            $item_average_rating = $this->getAverageRating();
            $this->item_average_rating = $item_average_rating;
            $this->save();
        }
        else
        {
            $this->item_average_rating = null;
            $this->save();
        }
    }

    public function getStarsCountRating(int $stars)
    {
        return DB::table('reviews')
            ->where('approved', self::ITEM_REVIEW_APPROVED)
            ->where('reviewrateable_id', $this->id)
            ->where('rating', $stars)
            ->count();
    }

    public function getApprovedRatingsSortBy(int $sort_by)
    {
        if($sort_by == self::ITEM_RATING_SORT_BY_NEWEST)
        {
            return DB::table('reviews')
                ->where('approved', self::ITEM_REVIEW_APPROVED)
                ->where('reviewrateable_id', $this->id)
                ->orderBy('updated_at', 'desc')
                ->get();
        }
        elseif($sort_by == self::ITEM_RATING_SORT_BY_OLDEST)
        {
            return DB::table('reviews')
                ->where('approved', self::ITEM_REVIEW_APPROVED)
                ->where('reviewrateable_id', $this->id)
                ->orderBy('updated_at', 'asc')
                ->get();
        }
        elseif($sort_by == self::ITEM_RATING_SORT_BY_HIGHEST)
        {
            return DB::table('reviews')
                ->where('approved', self::ITEM_REVIEW_APPROVED)
                ->where('reviewrateable_id', $this->id)
                ->orderBy('rating', 'desc')
                ->get();
        }
        elseif($sort_by == self::ITEM_RATING_SORT_BY_LOWEST)
        {
            return DB::table('reviews')
                ->where('approved', self::ITEM_REVIEW_APPROVED)
                ->where('reviewrateable_id', $this->id)
                ->orderBy('rating', 'asc')
                ->get();
        }
        else
        {
            return DB::table('reviews')
                ->where('approved', self::ITEM_REVIEW_APPROVED)
                ->where('reviewrateable_id', $this->id)
                ->orderBy('updated_at', 'desc')
                ->get();
        }

    }

    public function insertReviewGalleriesByReviewId(int $review_id,
                                                    string $review_image_gallery_name,
                                                    string $review_image_gallery_thumb_name)
    {
        DB::table('review_image_galleries')->insert(
            array(
                'review_id' => $review_id,
                'review_image_gallery_name' => $review_image_gallery_name,
                'review_image_gallery_thumb_name' => $review_image_gallery_thumb_name,
                'review_image_gallery_size' => null,
            )
        );
    }

    public function getReviewGalleriesByReviewId(int $review_id)
    {
        return DB::table('review_image_galleries')
            ->where('review_id', $review_id)
            ->get();
    }

    public function reviewGalleryCountByReviewId(int $review_id)
    {
        return DB::table('review_image_galleries')
            ->where('review_id', $review_id)
            ->count();
    }

    public function hasClaimed()
    {
        return $this->claims()
            ->where('item_claim_status', ItemClaim::ITEM_CLAIM_STATUS_APPROVED)
            ->count();
    }

    public function getClaimedUser()
    {
        if($this->hasClaimed())
        {
            $item_claim = $this->claims()
                ->where('item_claim_status', ItemClaim::ITEM_CLAIM_STATUS_APPROVED)
                ->first();

            return $item_claim->user()->first();
        }
        else
        {
            return null;
        }
    }

    /**
     * Function used by Admin/ItemClaimController/approveItemClaim
     *
     * after the item ownership change, the messages belong to the item will also change the message owner to the
     * new time owner.
     *
     * @param $old_owner_user_id
     * @param $new_owner_user_id
     */
    public function transferMessageOwner(int $old_owner_user_id, int $new_owner_user_id)
    {
        // first get all the threads related to this item.
        $threads = DB::table('thread_item_rels')
            ->where('item_id', $this->id)
            ->get();

        // check participants, if the new owner in the participant of the thread, then do nothing.
        foreach($threads as $key => $thread)
        {
            $new_owner_participant_exist = DB::table('participants')
                ->where('thread_id', $thread->thread_id)
                ->where('user_id', $new_owner_user_id)
                ->count();

            if($new_owner_participant_exist == 0)
            {
                DB::table('participants')
                    ->where('thread_id', $thread->thread_id)
                    ->where('user_id', $old_owner_user_id)
                    ->update(array(
                        'user_id' => $new_owner_user_id,
                        ));
            }

            $new_owner_message_exist = DB::table('messages')
                ->where('thread_id', $thread->thread_id)
                ->where('user_id', $new_owner_user_id)
                ->count();

            if($new_owner_message_exist == 0)
            {
                DB::table('messages')
                    ->where('thread_id', $thread->thread_id)
                    ->where('user_id', $old_owner_user_id)
                    ->update(array(
                        'user_id' => $new_owner_user_id,
                        ));
            }
        }
    }

    /**
     * Check if the item has collected product in the listing page
     *
     * @param Product $product
     * @return bool
     */
    public function hasCollectedProduct(Product $product)
    {
        $item_sections = $this->itemSections()
            ->where('item_section_status', ItemSection::STATUS_PUBLISHED)
            ->get();

        $item_section_ids = array();
        foreach($item_sections as $key => $item_section)
        {
            $item_section_ids[] = $item_section->id;
        }

        $item_section_collections = ItemSectionCollection::whereIn('item_section_id', $item_section_ids)
            ->where('item_section_collection_collectible_type', ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
            ->where('item_section_collection_collectible_id', $product->id)
            ->count();

        return $item_section_collections > 0 ? true : false;
    }


    /**
     * Transfer the ownership of the item to another user
     *
     * @param int $user_id
     */
    public function transferItemOwner(int $user_id)
    {
        // #1 - transfer the messages ownership
        $this->transferMessageOwner($this->user_id, $user_id);

        // #2 - delete all item section collections
        $item_sections = $this->itemSections()->get();
        foreach($item_sections as $item_sections_key => $item_section)
        {
            $item_section_collections = $item_section->itemSectionCollections()->get();
            foreach($item_section_collections as $item_section_collections_key => $item_section_collection)
            {
                $item_section_collection->delete();
            }
            // update item section status to draft so that it won't show on business listing page
            $item_section->item_section_status = ItemSection::STATUS_DRAFT;
            $item_section->save();
        }

        // #3 - change the item ownership to the claim requested user
        $this->user_id = $user_id;
        $this->save();
    }

    public function setApproved()
    {
        $this->item_status = Item::ITEM_PUBLISHED;
        $this->save();
    }

    public function setDisapproved()
    {
        $this->item_status = Item::ITEM_SUBMITTED;
        $this->save();
    }

    public function setSuspended()
    {
        $this->item_status = Item::ITEM_SUSPENDED;
        $this->save();
    }

    public function deleteItemFeatureImage()
    {
        if(!empty($this->item_image))
        {
            if(Storage::disk('public')->exists('item/' . $this->item_image)){
                Storage::disk('public')->delete('item/' . $this->item_image);
            }

            $this->item_image = null;
        }
        if(!empty($this->item_image_medium))
        {
            if(Storage::disk('public')->exists('item/' . $this->item_image_medium)){
                Storage::disk('public')->delete('item/' . $this->item_image_medium);
            }

            $this->item_image_medium = null;
        }
        if(!empty($this->item_image_small))
        {
            if(Storage::disk('public')->exists('item/' . $this->item_image_small)){
                Storage::disk('public')->delete('item/' . $this->item_image_small);
            }

            $this->item_image_small = null;
        }
        if(!empty($this->item_image_tiny))
        {
            if(Storage::disk('public')->exists('item/' . $this->item_image_tiny)){
                Storage::disk('public')->delete('item/' . $this->item_image_tiny);
            }

            $this->item_image_tiny = null;
        }
        if(!empty($this->item_image_blur))
        {
            if(Storage::disk('public')->exists('item/' . $this->item_image_blur)){
                Storage::disk('public')->delete('item/' . $this->item_image_blur);
            }

            $this->item_image_blur = null;
        }

        $this->save();
    }

    public function hasOpened()
    {
        $opening_hours_array_monday = array();
        $opening_hours_array_tuesday = array();
        $opening_hours_array_wednesday = array();
        $opening_hours_array_thursday = array();
        $opening_hours_array_friday = array();
        $opening_hours_array_saturday = array();
        $opening_hours_array_sunday = array();
        $opening_hour_exceptions_array = array();

        $item_hours = $this->itemHours()->get();
        foreach($item_hours as $item_hours_key => $item_hour)
        {
            $item_hour_open_time = substr($item_hour->item_hour_open_time, 0, -3);
            $item_hour_close_time = substr($item_hour->item_hour_close_time, 0, -3);

            if($item_hour->item_hour_day_of_week == ItemHour::DAY_OF_WEEK_MONDAY)
            {
                $opening_hours_array_monday[] = $item_hour_open_time . "-" . $item_hour_close_time;
            }
            elseif($item_hour->item_hour_day_of_week == ItemHour::DAY_OF_WEEK_TUESDAY)
            {
                $opening_hours_array_tuesday[] = $item_hour_open_time . "-" . $item_hour_close_time;
            }
            elseif($item_hour->item_hour_day_of_week == ItemHour::DAY_OF_WEEK_WEDNESDAY)
            {
                $opening_hours_array_wednesday[] = $item_hour_open_time . "-" . $item_hour_close_time;
            }
            elseif($item_hour->item_hour_day_of_week == ItemHour::DAY_OF_WEEK_THURSDAY)
            {
                $opening_hours_array_thursday[] = $item_hour_open_time . "-" . $item_hour_close_time;
            }
            elseif($item_hour->item_hour_day_of_week == ItemHour::DAY_OF_WEEK_FRIDAY)
            {
                $opening_hours_array_friday[] = $item_hour_open_time . "-" . $item_hour_close_time;
            }
            elseif($item_hour->item_hour_day_of_week == ItemHour::DAY_OF_WEEK_SATURDAY)
            {
                $opening_hours_array_saturday[] = $item_hour_open_time . "-" . $item_hour_close_time;
            }
            elseif($item_hour->item_hour_day_of_week == ItemHour::DAY_OF_WEEK_SUNDAY)
            {
                $opening_hours_array_sunday[] = $item_hour_open_time . "-" . $item_hour_close_time;
            }
        }

        $item_hour_exceptions = $this->itemHourExceptions()->get();
        foreach($item_hour_exceptions as $item_hour_exceptions_key => $item_hour_exception)
        {
            $item_hour_exception_open_time = empty($item_hour_exception->item_hour_exception_open_time) ? null : substr($item_hour_exception->item_hour_exception_open_time, 0, -3);
            $item_hour_exception_close_time = empty($item_hour_exception->item_hour_exception_close_time) ? null : substr($item_hour_exception->item_hour_exception_close_time, 0, -3);

            if(!empty($item_hour_exception_open_time) && !empty($item_hour_exception_close_time))
            {
                $opening_hour_exceptions_array[$item_hour_exception->item_hour_exception_date][] = $item_hour_exception_open_time . "-" . $item_hour_exception_close_time;
            }
            else
            {
                $opening_hour_exceptions_array[$item_hour_exception->item_hour_exception_date] = [];
            }
        }

        $opening_hours_obj = OpeningHours::createAndMergeOverlappingRanges([
            'monday' => $opening_hours_array_monday,
            'tuesday' => $opening_hours_array_tuesday,
            'wednesday' => $opening_hours_array_wednesday,
            'thursday' => $opening_hours_array_thursday,
            'friday' => $opening_hours_array_friday,
            'saturday' => $opening_hours_array_saturday,
            'sunday' => $opening_hours_array_sunday,
            'exceptions' => $opening_hour_exceptions_array,
        ], $this->item_hour_time_zone);

        return $opening_hours_obj->isOpen();
    }

    public function insertHours($item_hour_day_of_week, $item_hour_open_time, $item_hour_close_time)
    {
        if($item_hour_day_of_week >= ItemHour::DAY_OF_WEEK_MONDAY && $item_hour_day_of_week <= ItemHour::DAY_OF_WEEK_SUNDAY)
        {
            $item_hour_open_hour = intval(substr($item_hour_open_time, 0, 2));
            $item_hour_close_hour = intval(substr($item_hour_close_time, 0, 2));

            if($item_hour_open_hour >= 0 && $item_hour_close_hour >=0 && $item_hour_open_hour <= $item_hour_close_hour && $item_hour_close_hour <= 24)
            {
                $item_hour_open_time_format = $item_hour_open_time . ':00';
                $item_hour_close_time_format = $item_hour_close_time . ':00';

                if($item_hour_open_hour == 24)
                {
                    $item_hour_open_time_format = '24:00:00';
                }

                if($item_hour_close_hour == 24)
                {
                    $item_hour_close_time_format = '24:00:00';
                }

                if($item_hour_open_time_format != $item_hour_close_time_format)
                {
                    $create_item_hour = new ItemHour(array(
                        'item_id' => $this->id,
                        'item_hour_day_of_week' => $item_hour_day_of_week,
                        'item_hour_open_time' => $item_hour_open_time_format,
                        'item_hour_close_time' => $item_hour_close_time_format,
                    ));
                    $create_item_hour->save();

                    // check the saved open time and close time equal, otherwise delete. Because it could cause error
                    // when load the listing detail page.
                    $create_item_hour_find = ItemHour::find($create_item_hour->id);
                    if(substr($create_item_hour_find->item_hour_open_time, 0, 5) == substr($create_item_hour_find->item_hour_close_time, 0, 5))
                    {
                        $create_item_hour_find->delete();
                    }
                }
            }
        }
    }

    public function insertHourExceptions($item_hour_exception_date, $item_hour_exception_open_time=null, $item_hour_exception_close_time=null)
    {
        if(DateTime::createFromFormat('Y-m-d', $item_hour_exception_date) !== false)
        {
            if(!empty($item_hour_exception_open_time) && !empty($item_hour_exception_close_time))
            {
                $item_hour_exception_open_hour = intval(substr($item_hour_exception_open_time, 0, 2));
                $item_hour_exception_close_hour = intval(substr($item_hour_exception_close_time, 0, 2));

                if($item_hour_exception_open_hour >= 0 && $item_hour_exception_close_hour >= 0 && $item_hour_exception_open_hour <= $item_hour_exception_close_hour && $item_hour_exception_close_hour <= 24)
                {
                    $item_hour_exception_open_time_format = $item_hour_exception_open_time . ':00';
                    $item_hour_exception_close_time_format = $item_hour_exception_close_time . ':00';

                    if($item_hour_exception_open_hour == 24)
                    {
                        $item_hour_exception_open_time_format = '24:00:00';
                    }

                    if($item_hour_exception_close_hour == 24)
                    {
                        $item_hour_exception_close_time_format = '24:00:00';
                    }

                    if($item_hour_exception_open_time_format != $item_hour_exception_close_time_format)
                    {
                        $create_item_hour_exception = new ItemHourException(array(
                            'item_id' => $this->id,
                            'item_hour_exception_date' => $item_hour_exception_date,
                            'item_hour_exception_open_time' => $item_hour_exception_open_time_format,
                            'item_hour_exception_close_time' => $item_hour_exception_close_time_format,
                        ));
                        $create_item_hour_exception->save();

                        // check the saved open time and close time equal, otherwise delete. Because it could cause error
                        // when load the listing detail page.
                        $create_item_hour_exception_find = ItemHourException::find($create_item_hour_exception->id);
                        if(substr($create_item_hour_exception_find->item_hour_exception_open_time, 0, 5) == substr($create_item_hour_exception_find->item_hour_exception_close_time, 0, 5))
                        {
                            $create_item_hour_exception_find->delete();
                        }
                    }
                }
            }
            else
            {
                $create_item_hour_exception = new ItemHourException(array(
                    'item_id' => $this->id,
                    'item_hour_exception_date' => $item_hour_exception_date,
                    'item_hour_exception_open_time' => null,
                    'item_hour_exception_close_time' => null,
                ));
                $create_item_hour_exception->save();
            }
        }
    }

    /**
     * Delete item record
     * @throws Exception
     */
    public function deleteItem()
    {
        // #1 - delete galleries, and image files
        $item_image_gallery = $this->galleries()->get();

        foreach($item_image_gallery as $item_image_gallery_key => $gallery)
        {
            if(!empty($gallery->item_image_gallery_name))
            {
                if(Storage::disk('public')->exists('item/gallery/' . $gallery->item_image_gallery_name)){
                    Storage::disk('public')->delete('item/gallery/' . $gallery->item_image_gallery_name);
                }
            }

            if(!empty($gallery->item_image_gallery_thumb_name))
            {
                if(Storage::disk('public')->exists('item/gallery/' . $gallery->item_image_gallery_thumb_name)){
                    Storage::disk('public')->delete('item/gallery/' . $gallery->item_image_gallery_thumb_name);
                }
            }

            $gallery->delete();
        }

        // #2 - delete item features
        $item_features = $this->features()->get();
        foreach($item_features as $item_features_key => $item_feature)
        {
            $item_feature->delete();
        }

        // #3 - delete item feature image
        $this->deleteItemFeatureImage();

        // #4 - delete item review image galleries
        $reviews = DB::table('reviews')
            ->where('reviewrateable_id', $this->id)
            ->get();

        foreach($reviews as $reviews_key => $a_review)
        {
            $review_image_galleries = DB::table('review_image_galleries')
                ->where('review_id', $a_review->id)
                ->get();

            foreach($review_image_galleries as $review_image_galleries_key => $review_image_gallery)
            {
                if(Storage::disk('public')->exists('item/review/' . $review_image_gallery->review_image_gallery_name)){
                    Storage::disk('public')->delete('item/review/' . $review_image_gallery->review_image_gallery_name);
                }
                if(Storage::disk('public')->exists('item/review/' . $review_image_gallery->review_image_gallery_thumb_name)){
                    Storage::disk('public')->delete('item/review/' . $review_image_gallery->review_image_gallery_thumb_name);
                }

                DB::table('review_image_galleries')
                    ->where('id', $review_image_gallery->id)
                    ->delete();
            }
        }

        // #5 - delete item reviews
        DB::table('reviews')
            ->where('reviewrateable_id', $this->id)
            ->delete();

        // #6 - delete item comments
        DB::table('comments')
            ->where('commentable_id', $this->id)
            ->delete();

        // #7 - delete all messages of this item
        $threads_item = ThreadItem::where('item_id', $this->id)->get();
        foreach($threads_item as $threads_item_key => $a_thread)
        {
            DB::table('participants')
                ->where('thread_id', $a_thread->thread_id)
                ->delete();
            DB::table('messages')
                ->where('thread_id', $a_thread->thread_id)
                ->delete();
            DB::table('threads')
                ->where('id', $a_thread->thread_id)
                ->delete();
        }
        ThreadItem::where('item_id', $this->id)->delete();

        // #8 - delete all saved items
        DB::table('item_user')
            ->where('item_id', $this->id)
            ->delete();

        // #9 - detach all categories
        $this->allCategories()->sync([]);

        // #10 - delete all business claims
        $item_claims = $this->claims()->get();
        foreach($item_claims as $item_claims_key => $item_claim)
        {
            $item_claim->deleteItemClaim();
        }

        // #11 - delete all item sections and collections
        $item_sections = $this->itemSections()->get();
        foreach($item_sections as $item_sections_key => $item_section)
        {
            // delete all item section collections of this item section
            $item_section_collections = $item_section->itemSectionCollections()->get();
            foreach($item_section_collections as $item_section_collections_key => $item_section_collection)
            {
                $item_section_collection->delete();
            }
            $item_section->delete();
        }

        // #12 - delete item leads
        $item_leads = $this->itemLeads()->get();
        foreach($item_leads as $item_leads_key => $item_lead)
        {
            $item_lead->deleteItemLead();
        }

        // #13 - delete item hours
        if(Schema::hasTable('item_hours'))
        {
            $this->itemHours()->delete();
        }

        // #14 - delete item hour exceptions
        if(Schema::hasTable('item_hour_exceptions'))
        {
            $this->itemHourExceptions()->delete();
        }

        // #15 - delete the item record
        $this->delete();
    }
}

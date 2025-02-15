<?php

namespace App\Http\Controllers\User;

use App\Category;
use App\City;
use App\Country;
use App\CustomField;
use App\Http\Controllers\Controller;
use App\Item;
use App\ItemFeature;
use App\ItemHour;
use App\ItemHourException;
use App\ItemSection;
use App\ItemSectionCollection;
use App\Product;
use App\SettingItem;
use App\State;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use DateTimeZone;
use DateTime;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.user.item.items', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        /**
         * Start initial filter
         */
        $request_query_array = $request->query();

        $all_printable_categories = new Category();
        $all_printable_categories = $all_printable_categories->getPrintableCategoriesNoDash();

        // filter search query
        $search_query = empty($request->search_query) ? null : $request->search_query;
        $search_values = !empty($search_query) ? preg_split('/\s+/', $search_query, -1, PREG_SPLIT_NO_EMPTY) : array();

        // filter categories
        $filter_categories = empty($request->filter_categories) ? array() : $request->filter_categories;

        $filter_category_ids = array();
        if(count($filter_categories) == 0)
        {
            foreach($all_printable_categories as $all_printable_categories_key => $printable_category)
            {
                $filter_category_ids[] = $printable_category['category_id'];
            }
        }
        else
        {
            $filter_category_ids = $filter_categories;
        }

        // filter location
        $filter_country = empty($request->filter_country) ? null : $request->filter_country;
        $filter_state = empty($request->filter_state) ? null : $request->filter_state;
        $filter_city = empty($request->filter_city) ? null : $request->filter_city;

        $all_countries = Country::orderBy('country_name')->get();
        $all_states = collect([]);
        $all_cities = collect([]);
        if(!empty($filter_country))
        {
            $country = Country::find($filter_country);
            $all_states = $country->states()->orderBy('state_name')->get();
        }
        if(!empty($filter_state))
        {
            $state = State::find($filter_state);
            $all_cities = $state->cities()->orderBy('city_name')->get();
        }

        // filter item status & featured
        $filter_item_status = $request->filter_item_status;
        if(empty($filter_item_status))
        {
            $filter_item_status = array(Item::ITEM_SUBMITTED, Item::ITEM_PUBLISHED, Item::ITEM_SUSPENDED);
        }
        $filter_item_featured = $request->filter_item_featured;
        if(empty($filter_item_featured))
        {
            $filter_item_featured = array(Item::ITEM_FEATURED, Item::ITEM_NOT_FEATURED);
        }
        $filter_item_type = $request->filter_item_type;
        if(empty($filter_item_type))
        {
            $filter_item_type = array(Item::ITEM_TYPE_REGULAR, Item::ITEM_TYPE_ONLINE);
        }

        // filter sort by
        if($search_query)
        {
            $filter_sort_by = Item::ITEMS_SORT_BY_MOST_RELEVANT;
        }
        else
        {
            $filter_sort_by = empty($request->filter_sort_by) ? Item::ITEMS_SORT_BY_NEWEST_CREATED : $request->filter_sort_by;
        }

        // filter rows per page
        $filter_count_per_page = empty($request->filter_count_per_page) ? Item::COUNT_PER_PAGE_10 : $request->filter_count_per_page;
        /**
         * End initial filter
         */

        /**
         * Start build query
         */
        $login_user = Auth::user();

        $items_query = Item::query();

        $items_query->select('items.*');

        $items_query->where('user_id', $login_user->id);

        // categories
        $items_query->join('category_item as ci', 'items.id', '=', 'ci.item_id')
            ->whereIn("ci.category_id", $filter_category_ids);

        // search query
        if(is_array($search_values) && count($search_values) > 0)
        {
            $items_query->where(function ($query) use ($search_values) {
                foreach($search_values as $search_values_key => $search_value)
                {
                    $query->where('items.item_title', 'LIKE', "%".$search_value."%")
                        ->orWhere('items.item_categories_string', 'LIKE', "%".$search_value."%");
                }
            });
        }

        // location
        if(!empty($filter_country))
        {
            $items_query->where('items.country_id', $filter_country);
        }
        if(!empty($filter_state))
        {
            $items_query->where('items.state_id', $filter_state);
        }
        if(!empty($filter_city))
        {
            $items_query->where('items.city_id', $filter_city);
        }

        // item status
        $items_query->whereIn('items.item_status', $filter_item_status);

        // item featured
        $items_query->whereIn('items.item_featured', $filter_item_featured);

        // item type
        $items_query->whereIn('items.item_type', $filter_item_type);

        // sort by
        if($filter_sort_by == Item::ITEMS_SORT_BY_NEWEST_CREATED)
        {
            $items_query->orderBy('items.created_at', 'DESC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_OLDEST_CREATED)
        {
            $items_query->orderBy('items.created_at', 'ASC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_NEWEST_UPDATED)
        {
            $items_query->orderBy('items.updated_at', 'DESC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_OLDEST_UPDATED)
        {
            $items_query->orderBy('items.updated_at', 'ASC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_HIGHEST_RATING)
        {
            $items_query->orderBy('items.item_average_rating', 'DESC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_LOWEST_RATING)
        {
            $items_query->orderBy('items.item_average_rating', 'ASC');
        }
        /**
         * End build query
         */

        $items_query->distinct('items.id');

        /**
         * Start getting query result
         */
        $items_count = $items_query->count();
        $items = $items_query->paginate($filter_count_per_page);

        $querystringArray = [
            'search_query' => $search_query,
            'filter_categories' => $filter_categories,
            'filter_country' => $filter_country,
            'filter_state' => $filter_state,
            'filter_city' => $filter_city,
            'filter_item_status' => $filter_item_status,
            'filter_item_featured' => $filter_item_featured,
            'filter_sort_by' => $filter_sort_by,
            'filter_count_per_page' => $filter_count_per_page,
        ];

        $pagination = $items->appends($querystringArray);
        /**
         * End getting query result
         */

        /**
         * Start sorting the search by relevance
         */
        $props = [
            'item_title',
            'item_categories_string',
        ];

        $items = $items->sortByDesc(function($free_collection, $free_collection_key) use ($search_values, $props) {

            // The bigger the weight, the higher the record
            $weight = 0;
            // Iterate through search terms
            foreach($search_values as $search_values_key => $search_value)
            {
                // Iterate through $props
                foreach($props as $prop)
                {
                    if(stripos($free_collection->$prop, $search_value) !== false)
                    {
                        // Increase weight if the search term is found
                        if($prop == 'item_title')
                        {
                            $weight += 9;
                        }
                        else
                        {
                            $weight += 1;
                        }
                    }

                }
            }
            return $weight;
        });
        /**
         * End sorting the search by relevance
         */

        return response()->view('backend.user.item.index',
            compact('items', 'items_count', 'all_printable_categories', 'filter_categories',
                'filter_country', 'filter_state', 'filter_city', 'filter_item_status', 'filter_item_featured',
                'filter_item_type', 'filter_sort_by', 'filter_count_per_page', 'all_countries', 'all_states',
                'all_cities', 'request_query_array', 'search_query', 'pagination'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.user.item.create-item', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_categories = new Category();
        $all_categories = $all_categories->getPrintableCategories();

        $category_ids = empty($request->category) ? array() : $request->category;

        if(!empty($category_ids) && !is_array($category_ids))
        {
            return redirect()->back();
        }

        $all_customFields = collect();

        if(count($category_ids) > 0)
        {
            $all_customFields = new CustomField();
            $all_customFields = $all_customFields->getDistinctCustomFieldsByCategories($category_ids);
        }

        /**
         * Start initial country selector
         */
        $all_countries = Country::orderBy('country_name')->get();
        /**
         * End initial country selector
         */

        /**
         * Start initial time zone selector
         */
        $time_zone_identifiers = DateTimeZone::listIdentifiers();
        /**
         * End initial time zone selector
         */

        $setting_item_max_gallery_photos = $settings->settingItem->setting_item_max_gallery_photos;
        $setting_site_location_lat = $settings->setting_site_location_lat;
        $setting_site_location_lng = $settings->setting_site_location_lng;

        /**
         * Start initial item_featured selector
         */
        $login_user = Auth::user();
        $show_item_featured_selector = false;
        if($login_user->hasPaidSubscription())
        {
            $show_item_featured_selector = true;
        }
        elseif(is_null($login_user->subscription->plan->plan_max_featured_listing) || $login_user->subscription->plan->plan_max_featured_listing > 0)
        {
            $show_item_featured_selector = true;
        }
        /**
         * End initial item_featured selector
         */

        return response()->view('backend.user.item.create',
            compact('all_categories', 'category_ids', 'all_customFields', 'setting_item_max_gallery_photos',
                'setting_site_location_lat', 'setting_site_location_lng', 'all_countries', 'show_item_featured_selector',
                'time_zone_identifiers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start check plan quota
         */
        $login_user = Auth::user();

        if($request->item_featured == Item::ITEM_FEATURED)
        {
            if(!$login_user->canFeatureItem())
            {
                \Session::flash('flash_message', __('alert.item-created-error-quota'));
                \Session::flash('flash_type', 'danger');
                return redirect()->back()->withInput($request->input());
            }
        }
        else
        {
            if(!$login_user->canFreeItem())
            {
                \Session::flash('flash_message', __('theme_directory_hub.plan.alert.free-plan-quota-reached'));
                \Session::flash('flash_type', 'danger');
                return redirect()->back()->withInput($request->input());
            }
        }
        /**
         * End check plan quota
         */

        // prepare rule for general information
        $validate_rule = [
            'category' => 'required',
            'category.*' => 'numeric',
            'item_featured' => 'required|numeric',
            'item_title' => 'required|max:255',
            'item_description' => 'nullable',
            'city_id' => 'nullable|numeric',
            'state_id' => 'nullable|numeric',
            'country_id' => 'nullable|numeric',
            'item_postal_code' => 'nullable|max:255',
            'item_phone' => 'nullable|max:255',
            'item_website' => 'nullable|url|max:255',
            'item_social_facebook' => 'nullable|url|max:255',
            'item_social_twitter' => 'nullable|url|max:255',
            'item_social_linkedin' => 'nullable|url|max:255',
            'item_youtube_id' => 'nullable|max:255',
            'item_type' => 'required|numeric|in:1,2',
            'item_hour_time_zone' => 'required|max:255',
            'item_hour_show_hours' => 'required|numeric|in:1,2',
            'item_social_instagram' => 'nullable|max:255',
            'item_social_whatsapp' => 'nullable|numeric',
        ];

        // validate category_ids
        $select_categories = $request->category;

        foreach($select_categories as $select_categories_key => $select_category)
        {
            $select_category = Category::find($select_category);
            if(!$select_category)
            {
                throw ValidationException::withMessages(
                    [
                        'category' => __('prefer_country.category-not-found'),
                    ]);
            }

            // prepare validate rule for custom fields
            $custom_field_validation = array();
            $custom_field_link = $select_category->allCustomFields()
                ->where('custom_field_type', CustomField::TYPE_LINK)
                ->get();

            if($custom_field_link->count() > 0)
            {
                foreach($custom_field_link as $custom_field_link_key => $a_link)
                {
                    $custom_field_validation[str_slug($a_link->custom_field_name . $a_link->id)] = 'nullable|url';
                }
            }

            $validate_rule = array_merge($validate_rule, $custom_field_validation);
        }

        // validate request
        $request->validate($validate_rule);

        /**
         * Start validate location (city, state, country, lat, lng)
         */
        $item_type = $request->item_type == Item::ITEM_TYPE_REGULAR ? Item::ITEM_TYPE_REGULAR : Item::ITEM_TYPE_ONLINE;

        $select_country_id = null;
        $select_state_id = null;
        $select_city_id = null;
        $select_item_lat = null;
        $select_item_lng = null;
        $item_location_str = "";

        $item_postal_code = $request->item_postal_code;

        if($item_type == Item::ITEM_TYPE_REGULAR)
        {
            // validate country_id
            $select_country = Country::find($request->country_id);
            if(!$select_country)
            {
                throw ValidationException::withMessages(
                    [
                        'country_id' => __('prefer_country.country-not-found'),
                    ]);
            }

            // validate state_id
            $select_state = State::find($request->state_id);
            if(!$select_state)
            {
                throw ValidationException::withMessages(
                    [
                        'state_id' => __('prefer_country.state-not-found'),
                    ]);
            }
            // validate city_id
            $select_city = City::find($request->city_id);
            if(!$select_city)
            {
                throw ValidationException::withMessages(
                    [
                        'city_id' => __('prefer_country.city-not-found'),
                    ]);
            }

            $select_country_id = $select_country->id;
            $select_state_id = $select_state->id;
            $select_city_id = $select_city->id;

            if(empty($request->item_lat) || empty($request->item_lng))
            {
                $select_item_lat = $select_city->city_lat;
                $select_item_lng = $select_city->city_lng;
            }
            else
            {
                $select_item_lat = $request->item_lat;
                $select_item_lng = $request->item_lng;
            }

            $item_location_str = $select_city->city_name . ' ' . $select_state->state_name . ' ' . $select_country->country_name . ' ' . $item_postal_code;
        }
        /**
         * End validate location (city, state, country, lat, lng)
         */

        // prepare new item data
        $user_id = $login_user->id;

        $item_status = $settings->settingItem->setting_item_auto_approval_enable == SettingItem::SITE_ITEM_AUTO_APPROVAL_ENABLED ? Item::ITEM_PUBLISHED : Item::ITEM_SUBMITTED;

        $item_featured = $request->item_featured == Item::ITEM_FEATURED ? Item::ITEM_FEATURED : Item::ITEM_NOT_FEATURED;
        $item_title = $request->item_title;

        // generate item slug based on combination of uniq id and item_title slug
        $item_slug = str_slug($item_title);
        $item_slug_exist = Item::where('item_slug', $item_slug)->count();
        if($item_slug_exist > 0)
        {
            $item_slug = $item_slug . '-' . uniqid();
        }

        $item_description = empty($request->item_description) ? null : $request->item_description;
        $item_address = $request->item_address;
        $item_address_hide = $request->item_address_hide == Item::ITEM_ADDR_HIDE ? Item::ITEM_ADDR_HIDE : Item::ITEM_ADDR_NOT_HIDE;

        $city_id = $select_city_id;
        $state_id = $select_state_id;
        $country_id = $select_country_id;

        $item_lat = $select_item_lat;
        $item_lng = $select_item_lng;

        $item_youtube_id = $request->item_youtube_id;

        $item_phone = empty($request->item_phone) ? null : $request->item_phone;
        $item_website = $request->item_website;
        $item_social_facebook = $request->item_social_facebook;
        $item_social_twitter = $request->item_social_twitter;
        $item_social_linkedin = $request->item_social_linkedin;
        $item_social_instagram = $request->item_social_instagram;
        $item_social_whatsapp = ltrim($request->item_social_whatsapp, '0');

        $item_hour_time_zone = $request->item_hour_time_zone;
        $item_hour_show_hours = $request->item_hour_show_hours == Item::ITEM_HOUR_SHOW ? Item::ITEM_HOUR_SHOW : Item::ITEM_HOUR_NOT_SHOW;

        // start upload feature image
        $feature_image = $request->feature_image;
        $item_feature_image_name = null;
        $item_feature_image_name_medium = null;
        $item_feature_image_name_small = null;
        $item_feature_image_name_tiny = null;
        $item_feature_image_name_blur = null;
        if(!empty($feature_image)){

            $currentDate = Carbon::now()->toDateString();

            $item_feature_image_name = $item_slug . '-' . $currentDate . '-' . uniqid() . '.jpg';
            $item_feature_image_name_medium = $item_slug . '-' . $currentDate . '-' . uniqid() . '-medium.jpg';
            $item_feature_image_name_small = $item_slug . '-' . $currentDate . '-' . uniqid() . '-small.jpg';
            $item_feature_image_name_tiny = $item_slug . '-' . $currentDate . '-' . uniqid() . '-tiny.jpg';

            // blur feature image name
            $item_feature_image_name_blur = $item_slug . '-' . $currentDate . '-' . uniqid() . '-blur.jpg';

            if(!Storage::disk('public')->exists('item')){
                Storage::disk('public')->makeDirectory('item');
            }

            $item_feature_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))->stream('jpg', 70);
            Storage::disk('public')->put('item/'.$item_feature_image_name, $item_feature_image);

            // medium size
            $item_feature_image_medium = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))
                ->resize(350, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $item_feature_image_medium = $item_feature_image_medium->stream('jpg', 70);
            Storage::disk('public')->put('item/'.$item_feature_image_name_medium, $item_feature_image_medium);

            // small size
            $item_feature_image_small = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))
                ->resize(230, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $item_feature_image_small = $item_feature_image_small->stream('jpg', 70);
            Storage::disk('public')->put('item/'.$item_feature_image_name_small, $item_feature_image_small);

            // tiny size
            $item_feature_image_tiny = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))
                ->resize(160, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $item_feature_image_tiny = $item_feature_image_tiny->stream('jpg', 70);
            Storage::disk('public')->put('item/'.$item_feature_image_name_tiny, $item_feature_image_tiny);

            // blur feature image
            $item_feature_image_blur = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)));
            $item_feature_image_blur->blur(50);
            $item_feature_image_blur->stream('jpg', 70);
            Storage::disk('public')->put('item/'.$item_feature_image_name_blur, $item_feature_image_blur);
        }

        // start saving category string to item_categories_string column of items table
        $item_categories_string = "";
        foreach($select_categories as $select_categories_key => $select_category)
        {
            $item_categories_string = $item_categories_string . " " . Category::find($select_category)->category_name;
        }

        // fill new item data
        $new_item = new Item(array(
            'user_id' => $user_id,
            'item_status' => $item_status,
            'item_featured' => $item_featured,
            'item_title' => $item_title,
            'item_slug' => $item_slug,
            'item_description' => $item_description,
            'item_image' => $item_feature_image_name,
            'item_image_medium' => $item_feature_image_name_medium,
            'item_image_small' => $item_feature_image_name_small,
            'item_image_tiny' => $item_feature_image_name_tiny,
            'item_image_blur' => $item_feature_image_name_blur,
            'item_address' => $item_address,
            'item_address_hide' => $item_address_hide,
            'city_id' => $city_id,
            'state_id' => $state_id,
            'country_id' => $country_id,
            'item_postal_code' => $item_postal_code,
            'item_lat' => $item_lat,
            'item_lng' => $item_lng,
            'item_youtube_id' => $item_youtube_id,
            'item_phone' => $item_phone,
            'item_website' => $item_website,
            'item_social_facebook' => $item_social_facebook,
            'item_social_twitter' => $item_social_twitter,
            'item_social_linkedin' => $item_social_linkedin,
            'item_categories_string' => $item_categories_string,
            'item_location_str' => $item_location_str,
            'item_type' => $item_type,
            'item_hour_time_zone' => $item_hour_time_zone,
            'item_hour_show_hours' => $item_hour_show_hours,
            'item_social_instagram' => $item_social_instagram,
            'item_social_whatsapp' => $item_social_whatsapp,
        ));
        $new_item->save();

        $new_item->allCategories()->sync($select_categories);

        // start to save custom fields data
        $category_custom_fields = new CustomField();
        $category_custom_fields = $category_custom_fields->getDistinctCustomFieldsByCategories($select_categories);

        if($category_custom_fields->count() > 0)
        {
            foreach($category_custom_fields as $category_custom_fields_key => $custom_field)
            {
                if($custom_field->custom_field_type == CustomField::TYPE_MULTI_SELECT)
                {
                    $multi_select_values = $request->get(str_slug($custom_field->custom_field_name . $custom_field->id), '');
                    $multi_select_str = '';
                    if(is_array($multi_select_values))
                    {
                        foreach($multi_select_values as $multi_select_values_key => $value)
                        {
                            $multi_select_str .= $value . ', ';
                        }
                    }
                    $new_item_feature = new ItemFeature(array(
                        'custom_field_id' => $custom_field->id,
                        'item_feature_value' => empty($multi_select_str) ? '' : substr(trim($multi_select_str), 0, -1),
                    ));
                }
                else
                {
                    $new_item_feature = new ItemFeature(array(
                        'custom_field_id' => $custom_field->id,
                        'item_feature_value' => $request->get(str_slug($custom_field->custom_field_name . $custom_field->id), ''),
                    ));
                }

                $created_item_feature = $new_item->features()->save($new_item_feature);

                $new_item->item_features_string = $new_item->item_features_string . $created_item_feature->item_feature_value . " ";
                $new_item->save();
            }
        }

        // start to upload image galleries
        $image_gallary = $request->image_gallery;
        if(is_array($image_gallary) && count($image_gallary) > 0)
        {
            foreach($image_gallary as $image_gallary_key => $image)
            {
                // check if the listing's gallery images reach the max number of gallery images
                if($image_gallary_key < $settings->settingItem->setting_item_max_gallery_photos)
                {
                    $currentDate = Carbon::now()->toDateString();
                    $item_image_gallery_uniqid = uniqid();

                    $item_image_gallery['item_image_gallery_name'] = 'gallary-'.$currentDate.'-'.$item_image_gallery_uniqid.'.jpg';;
                    $item_image_gallery['item_image_gallery_thumb_name'] = 'gallary-'.$currentDate.'-'.$item_image_gallery_uniqid.'-thumb.jpg';

                    if(!Storage::disk('public')->exists('item/gallery')){
                        Storage::disk('public')->makeDirectory('item/gallery');
                    }

                    // original
                    $one_gallery_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))->stream('jpg', 80);
                    Storage::disk('public')->put('item/gallery/'.$item_image_gallery['item_image_gallery_name'], $one_gallery_image);

                    // thumb size
                    $one_gallery_image_thumb = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))
                        ->resize(null, 180, function($constraint) {
                            $constraint->aspectRatio();
                        });
                    $one_gallery_image_thumb = $one_gallery_image_thumb->stream('jpg', 70);
                    Storage::disk('public')->put('item/gallery/'.$item_image_gallery['item_image_gallery_thumb_name'], $one_gallery_image_thumb);

                    $created_item_image_gallery = $new_item->galleries()->create($item_image_gallery);
                }
            }
        }

        /**
         * Start save item hours
         */
        $item_hours = empty($request->item_hours) ? array() : $request->item_hours;

        foreach($item_hours as $item_hours_key => $item_hour)
        {
            $item_hour_record = explode(' ', $item_hour);

            if(count($item_hour_record) == 3)
            {
                $item_hour_day_of_week = intval($item_hour_record[0]);

                if($item_hour_day_of_week >= ItemHour::DAY_OF_WEEK_MONDAY && $item_hour_day_of_week <= ItemHour::DAY_OF_WEEK_SUNDAY)
                {
                    $item_hour_open_hour = intval(substr($item_hour_record[1], 0, 2));
                    $item_hour_close_hour = intval(substr($item_hour_record[2], 0, 2));

                    if($item_hour_open_hour <= $item_hour_close_hour)
                    {
                        $item_hour_open_time = $item_hour_record[1] . ':00';
                        $item_hour_close_time = $item_hour_record[2] . ':00';

                        if($item_hour_open_hour == 24)
                        {
                            $item_hour_open_time = '24:00:00';
                        }

                        if($item_hour_close_hour == 24)
                        {
                            $item_hour_close_time = '24:00:00';
                        }

                        if($item_hour_open_time != $item_hour_close_time)
                        {
                            $create_item_hour = new ItemHour(array(
                                'item_id' => $new_item->id,
                                'item_hour_day_of_week' => $item_hour_day_of_week,
                                'item_hour_open_time' => $item_hour_open_time,
                                'item_hour_close_time' => $item_hour_close_time,
                            ));
                            $create_item_hour->save();
                        }
                    }
                }
            }
        }
        /**
         * End save item hours
         */

        /**
         * Start save item hour exceptions
         */
        $item_hour_exceptions = empty($request->item_hour_exceptions) ? array() : $request->item_hour_exceptions;

        foreach($item_hour_exceptions as $item_hour_exceptions_key => $item_hour_exception)
        {
            $item_hour_exception_record = explode(' ', $item_hour_exception);

            $item_hour_exception_date = $item_hour_exception_record[0];

            if(DateTime::createFromFormat('Y-m-d', $item_hour_exception_date) !== false)
            {
                $item_hour_exception_open_time = null;
                $item_hour_exception_close_time = null;

                if(count($item_hour_exception_record) == 3)
                {
                    $item_hour_exception_open_time = $item_hour_exception_record[1] . ':00';
                    $item_hour_exception_close_time = $item_hour_exception_record[2] . ':00';

                    $item_hour_exception_open_hour = intval(substr($item_hour_exception_record[1], 0, 2));
                    $item_hour_exception_close_hour = intval(substr($item_hour_exception_record[2], 0, 2));

                    if($item_hour_exception_open_hour == 24)
                    {
                        $item_hour_exception_open_time = '24:00:00';
                    }

                    if($item_hour_exception_close_hour == 24)
                    {
                        $item_hour_exception_close_time = '24:00:00';
                    }

                    if($item_hour_exception_open_hour > $item_hour_exception_close_hour || $item_hour_exception_open_time == $item_hour_exception_close_time)
                    {
                        continue;
                    }
                }

                $create_item_hour_exception = new ItemHourException(array(
                    'item_id' => $new_item->id,
                    'item_hour_exception_date' => $item_hour_exception_date,
                    'item_hour_exception_open_time' => $item_hour_exception_open_time,
                    'item_hour_exception_close_time' => $item_hour_exception_close_time,
                ));
                $create_item_hour_exception->save();
            }
        }
        /**
         * End save item hour exceptions
         */

        // success, flash message
        \Session::flash('flash_message', __('alert.item-created'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.edit', ['item' => $new_item]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return RedirectResponse
     */
    public function show(Item $item)
    {
        return redirect()->route('page.item', ['item_slug' => $item->item_slug]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return Response
     */
    public function edit(Item $item)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        //SEOMeta::setTitle('Dashboard - Edit Listing - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.user.item.edit-item', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        Gate::authorize('edit-item', $item);

        $all_categories = new Category();
        $all_categories = $all_categories->getPrintableCategories();

        /**
         * Start initial country, state, city selector
         */
        $all_countries = Country::orderBy('country_name')->get();

        $all_states = collect([]);
        $all_cities = collect([]);

        if($item->item_type == Item::ITEM_TYPE_REGULAR)
        {
            $all_states = $item->country()->first()->states()->orderBy('state_name')->get();
            $all_cities = $item->state()->first()->cities()->orderBy('city_name')->get();
        }
        /**
         * End initial country, state, city selector
         */

        // get all custom fields based on the categories of the item
        $categories = $item->allCategories()->get();
        $category_ids = array();
        foreach($categories as $key => $category)
        {
            $category_ids[] = $category->id;
        }

        $custom_field_ids = DB::table('category_custom_field')
            ->whereIn('category_id', $category_ids)
            ->distinct('custom_field_id')
            ->get();

        $select_custom_field = array();
        foreach($custom_field_ids as $key => $custom_field_id)
        {
            $select_custom_field[] = $custom_field_id->custom_field_id;
        }

        $all_customFields = CustomField::whereIn('id', $select_custom_field)
            ->orderBy('custom_field_order')
            ->orderBy('created_at')
            ->get();

        /**
         * Start initial item_featured selector
         */
        $login_user = Auth::user();
        $show_item_featured_selector = false;
        if($login_user->hasPaidSubscription())
        {
            $show_item_featured_selector = true;
        }
        elseif(is_null($login_user->subscription->plan->plan_max_featured_listing) || $login_user->subscription->plan->plan_max_featured_listing > 0)
        {
            $show_item_featured_selector = true;
        }
        /**
         * End initial item_featured selector
         */

        $setting_item_max_gallery_photos = $settings->settingItem->setting_item_max_gallery_photos;

        /**
         * Start initial time zone selector
         */
        $time_zone_identifiers = DateTimeZone::listIdentifiers();
        /**
         * End initial time zone selector
         */

        /**
         * Start initial item hours and exceptions
         */
        $item_hours = $item->itemHours()->orderBy('item_hour_day_of_week')->get();
        $item_hour_exceptions = $item->itemHourExceptions()->orderBy('item_hour_exception_date')->get();
        /**
         * End initial item hours and exceptions
         */

        return response()->view('backend.user.item.edit',
            compact('all_countries', 'all_states', 'all_cities', 'all_customFields', 'item', 'categories',
            'all_categories', 'category_ids', 'show_item_featured_selector', 'setting_item_max_gallery_photos',
            'time_zone_identifiers', 'item_hours', 'item_hour_exceptions'));
    }

    /**
     * @param Request $request
     * @param Item $item
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateItemSlug(Request $request, Item $item)
    {
        $request->validate([
            'item_slug' => 'required|regex:/^[\w-]*$/|max:255',
        ]);

        $item_slug = $request->item_slug;

        $validate_error = array();

        $item_slug_exist = Item::where('item_slug', $item_slug)
            ->where('id', '!=', $item->id)
            ->count();

        if($item_slug_exist > 0)
        {
            $validate_error['item_slug'] = __('item_slug.alert.item-slug-exist');
        }

        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }

        $item->item_slug = $item_slug;
        $item->save();

        \Session::flash('flash_message', __('item_slug.alert.item-slug-update-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.edit', $item);
    }

    /**
     * @param Request $request
     * @param Item $item
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateItemCategory(Request $request, Item $item)
    {
        $request->validate([
            'category' => 'required',
            'category.*' => 'numeric',
        ]);

        // validate category_ids
        $select_categories = $request->category;

        $item_categories_string = "";

        foreach($select_categories as $key => $select_category)
        {
            $select_category = Category::find($select_category);
            if(!$select_category)
            {
                throw ValidationException::withMessages(
                    [
                        'category' => 'Category not found',
                    ]);
            }
            else
            {
                $item_categories_string = $item_categories_string . " " . $select_category->category_name;
            }
        }

        // update item category
        $item->allCategories()->sync($select_categories);

        // start saving item_categories_string
        $item->item_categories_string = $item_categories_string;

        if(!\Illuminate\Support\Facades\Auth::user()->isAdmin())
        {
            // if the user is regular user, then need approve this item category update
            $item->item_status = Item::ITEM_SUBMITTED;
        }
        $item->save();

        // success, flash message
        \Session::flash('flash_message', __('categories.item-category-update-alert'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.edit', $item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param \App\Item $item
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Item $item)
    {
        Gate::authorize('update-item', $item);

        $settings = app('site_global_settings');

        /**
         * Start check plan quota
         */
        $login_user = Auth::user();

        if($request->item_featured == Item::ITEM_FEATURED
            && $item->item_featured_by_admin == Item::ITEM_NOT_FEATURED_BY_ADMIN
            && $item->item_featured == Item::ITEM_NOT_FEATURED)
        {
            if(!$login_user->canFeatureItem())
            {
                \Session::flash('flash_message', __('alert.item-created-error-quota'));
                \Session::flash('flash_type', 'danger');
                return redirect()->back()->withInput($request->input());
            }
        }
        elseif($item->item_featured == Item::ITEM_FEATURED && $request->item_featured == Item::ITEM_NOT_FEATURED)
        {
            if(!$login_user->canFreeItem())
            {
                \Session::flash('flash_message', __('theme_directory_hub.plan.alert.free-plan-quota-reached'));
                \Session::flash('flash_type', 'danger');
                return redirect()->back()->withInput($request->input());
            }
        }
        /**
         * End check plan quota
         */

        // prepare rule for general information
        $validate_rule = [
            'item_featured' => 'required|numeric',
            'item_title' => 'required|max:255',
            'item_description' => 'nullable',
            'city_id' => 'nullable|numeric',
            'state_id' => 'nullable|numeric',
            'country_id' => 'nullable|numeric',
            'item_postal_code' => 'nullable|max:255',
            'item_phone' => 'nullable|max:255',
            'item_website' => 'nullable|url|max:255',
            'item_social_facebook' => 'nullable|url|max:255',
            'item_social_twitter' => 'nullable|url|max:255',
            'item_social_linkedin' => 'nullable|url|max:255',
            'item_youtube_id' => 'nullable|max:255',
            'item_type' => 'required|numeric|in:1,2',
            'item_hour_time_zone' => 'required|max:255',
            'item_hour_show_hours' => 'required|numeric|in:1,2',
            'item_social_instagram' => 'nullable|max:255',
            'item_social_whatsapp' => 'nullable|numeric',
        ];

        // prepare validate rule for custom fields
        $select_categories = $item->allCategories()->get();

        foreach($select_categories as $select_categories_key => $select_category)
        {
            $custom_field_validation = array();
            $custom_field_link = $select_category->allCustomFields()
                ->where('custom_field_type', CustomField::TYPE_LINK)
                ->get();

            if($custom_field_link->count() > 0)
            {
                foreach($custom_field_link as $custom_field_link_key => $a_link)
                {
                    $custom_field_validation[str_slug($a_link->custom_field_name . $a_link->id)] = 'nullable|url';
                }
            }

            $validate_rule = array_merge($validate_rule, $custom_field_validation);
        }

        // validate request
        $request->validate($validate_rule);

        /**
         * Start validate location (city, state, country, lat, lng)
         */
        $item_type = $request->item_type == Item::ITEM_TYPE_REGULAR ? Item::ITEM_TYPE_REGULAR : Item::ITEM_TYPE_ONLINE;

        $select_country_id = null;
        $select_state_id = null;
        $select_city_id = null;
        $select_item_lat = null;
        $select_item_lng = null;
        $item_location_str = "";

        $item_postal_code = $request->item_postal_code;

        if($item_type == Item::ITEM_TYPE_REGULAR)
        {
            // validate country_id
            $select_country = Country::find($request->country_id);
            if(!$select_country)
            {
                throw ValidationException::withMessages(
                    [
                        'country_id' => __('prefer_country.country-not-found'),
                    ]);
            }

            // validate state_id
            $select_state = State::find($request->state_id);
            if(!$select_state)
            {
                throw ValidationException::withMessages(
                    [
                        'state_id' => __('prefer_country.state-not-found'),
                    ]);
            }
            // validate city_id
            $select_city = City::find($request->city_id);
            if(!$select_city)
            {
                throw ValidationException::withMessages(
                    [
                        'city_id' => __('prefer_country.city-not-found'),
                    ]);
            }

            $select_country_id = $select_country->id;
            $select_state_id = $select_state->id;
            $select_city_id = $select_city->id;

            if(empty($request->item_lat) || empty($request->item_lng))
            {
                $select_item_lat = $select_city->city_lat;
                $select_item_lng = $select_city->city_lng;
            }
            else
            {
                $select_item_lat = $request->item_lat;
                $select_item_lng = $request->item_lng;
            }

            $item_location_str = $select_city->city_name . ' ' . $select_state->state_name . ' ' . $select_country->country_name . ' ' . $item_postal_code;
        }
        /**
         * End validate location (city, state, country, lat, lng)
         */

        // prepare new item data
        $item_featured = $request->item_featured == Item::ITEM_FEATURED ? Item::ITEM_FEATURED : Item::ITEM_NOT_FEATURED;
        $item_title = $request->item_title;

        $item_description = empty($request->item_description) ? null : $request->item_description;
        $item_address = $request->item_address;
        $item_address_hide = $request->item_address_hide == Item::ITEM_ADDR_HIDE ? Item::ITEM_ADDR_HIDE : Item::ITEM_ADDR_NOT_HIDE;

        $city_id = $select_city_id;
        $state_id = $select_state_id;
        $country_id = $select_country_id;

        $item_lat = $select_item_lat;
        $item_lng = $select_item_lng;

        $item_youtube_id = $request->item_youtube_id;

        $item_phone = empty($request->item_phone) ? null : $request->item_phone;
        $item_website = $request->item_website;
        $item_social_facebook = $request->item_social_facebook;
        $item_social_twitter = $request->item_social_twitter;
        $item_social_linkedin = $request->item_social_linkedin;
        $item_social_instagram = $request->item_social_instagram;
        $item_social_whatsapp = ltrim($request->item_social_whatsapp, '0');

        $item_hour_time_zone = $request->item_hour_time_zone;
        $item_hour_show_hours = $request->item_hour_show_hours == Item::ITEM_HOUR_SHOW ? Item::ITEM_HOUR_SHOW : Item::ITEM_HOUR_NOT_SHOW;

        // start upload feature image
        $feature_image = $request->feature_image;
        $item_feature_image_name = $item->item_image;
        $item_feature_image_name_medium = $item->item_image_medium;
        $item_feature_image_name_small = $item->item_image_small;
        $item_feature_image_name_tiny = $item->item_image_tiny;
        $item_feature_image_name_blur = $item->item_image_blur;

        if(!empty($feature_image)){

            $currentDate = Carbon::now()->toDateString();

            $item_feature_image_name = $item->item_slug . '-' . $currentDate . '-' . uniqid() . '.jpg';
            $item_feature_image_name_medium = $item->item_slug . '-' . $currentDate . '-' . uniqid() . '-medium.jpg';
            $item_feature_image_name_small = $item->item_slug . '-' . $currentDate . '-' . uniqid() . '-small.jpg';
            $item_feature_image_name_tiny = $item->item_slug . '-' . $currentDate . '-' . uniqid() . '-tiny.jpg';

            // blur feature image name
            $item_feature_image_name_blur = $item->item_slug . '-' . $currentDate . '-' . uniqid() . '-blur.jpg';

            if(!Storage::disk('public')->exists('item')){
                Storage::disk('public')->makeDirectory('item');
            }
            if(Storage::disk('public')->exists('item/' . $item->item_image)){

                Storage::disk('public')->delete('item/' . $item->item_image);
                Storage::disk('public')->delete('item/' . $item->item_image_medium);
                Storage::disk('public')->delete('item/' . $item->item_image_small);
                Storage::disk('public')->delete('item/' . $item->item_image_tiny);
                Storage::disk('public')->delete('item/' . $item->item_image_blur);
            }

            // original size
            $item_feature_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))->stream('jpg', 70);
            Storage::disk('public')->put('item/'.$item_feature_image_name, $item_feature_image);

            // medium size
            $item_feature_image_medium = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))
                ->resize(350, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $item_feature_image_medium = $item_feature_image_medium->stream('jpg', 70);
            Storage::disk('public')->put('item/'.$item_feature_image_name_medium, $item_feature_image_medium);

            // small size
            $item_feature_image_small = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))
                ->resize(230, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $item_feature_image_small = $item_feature_image_small->stream('jpg', 70);
            Storage::disk('public')->put('item/'.$item_feature_image_name_small, $item_feature_image_small);

            // tiny size
            $item_feature_image_tiny = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))
                ->resize(160, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $item_feature_image_tiny = $item_feature_image_tiny->stream('jpg', 70);
            Storage::disk('public')->put('item/'.$item_feature_image_name_tiny, $item_feature_image_tiny);

            // blur feature image
            $item_feature_image_blur = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)));
            $item_feature_image_blur->blur(50);
            $item_feature_image_blur->stream('jpg', 70);
            Storage::disk('public')->put('item/'.$item_feature_image_name_blur, $item_feature_image_blur);
        }

        // start saving category string to item_categories_string column of items table
        $item_categories_string = "";
        foreach($select_categories as $select_categories_key => $select_category)
        {
            $item_categories_string = $item_categories_string . " " . $select_category->category_name;
        }

        // do not change the item status for an update request
        // $item->item_status = Item::ITEM_SUBMITTED;

        $item->item_featured = $item_featured;
        $item->item_title = $item_title;

        $item->item_description = $item_description;

        $item->item_image = $item_feature_image_name;
        $item->item_image_medium = $item_feature_image_name_medium;
        $item->item_image_small = $item_feature_image_name_small;
        $item->item_image_tiny = $item_feature_image_name_tiny;
        $item->item_image_blur = $item_feature_image_name_blur;

        $item->item_address = $item_address;
        $item->item_address_hide = $item_address_hide;
        $item->city_id = $city_id;
        $item->state_id = $state_id;
        $item->country_id = $country_id;
        $item->item_postal_code = $item_postal_code;
        $item->item_lat = $item_lat;
        $item->item_lng = $item_lng;
        $item->item_youtube_id = $item_youtube_id;

        $item->item_phone = $item_phone;
        $item->item_website = $item_website;
        $item->item_social_facebook = $item_social_facebook;
        $item->item_social_twitter = $item_social_twitter;
        $item->item_social_linkedin = $item_social_linkedin;
        $item->item_social_instagram = $item_social_instagram;
        $item->item_social_whatsapp = $item_social_whatsapp;

        $item->item_features_string = null;
        $item->item_categories_string = $item_categories_string;
        $item->item_location_str = $item_location_str;

        $item->item_type = $item_type;

        $item->item_hour_time_zone = $item_hour_time_zone;
        $item->item_hour_show_hours = $item_hour_show_hours;

        $item->save();

        // start to save custom fields data
        $item->features()->delete();

        $item_categories = $item->allCategories()->get();
        $select_categories = array();
        foreach($item_categories as $item_categories_key => $item_category)
        {
            $select_categories[] = $item_category->id;
        }

        $category_custom_fields = new CustomField();
        $category_custom_fields = $category_custom_fields->getDistinctCustomFieldsByCategories($select_categories);

        if($category_custom_fields->count() > 0)
        {
            foreach($category_custom_fields as $category_custom_fields_key => $custom_field)
            {
                if($custom_field->custom_field_type == CustomField::TYPE_MULTI_SELECT)
                {
                    $multi_select_values = $request->get(str_slug($custom_field->custom_field_name . $custom_field->id), '');
                    $multi_select_str = '';
                    if(is_array($multi_select_values))
                    {
                        foreach($multi_select_values as $multi_select_values_key => $value)
                        {
                            $multi_select_str .= $value . ', ';
                        }
                    }
                    $new_item_feature = new ItemFeature(array(
                        'custom_field_id' => $custom_field->id,
                        'item_feature_value' => empty($multi_select_str) ? '' : substr(trim($multi_select_str), 0, -1),
                    ));
                }
                else
                {
                    $new_item_feature = new ItemFeature(array(
                        'custom_field_id' => $custom_field->id,
                        'item_feature_value' => $request->get(str_slug($custom_field->custom_field_name . $custom_field->id), ''),
                    ));
                }

                $created_item_feature = $item->features()->save($new_item_feature);

                $item->item_features_string = $item->item_features_string . $created_item_feature->item_feature_value . " ";
                $item->save();
            }
        }

        // start to upload image galleries
        $image_gallery = $request->image_gallery;
        if(is_array($image_gallery) && count($image_gallery) > 0)
        {
            $total_item_image_gallery = $item->galleries()->count();
            foreach($image_gallery as $image_gallery_key => $image)
            {
                // check if the listing's gallery images reach the max number of gallery images
                if($total_item_image_gallery + $image_gallery_key < $settings->settingItem->setting_item_max_gallery_photos)
                {
                    $currentDate = Carbon::now()->toDateString();
                    $item_image_gallery_uniqid = uniqid();

                    $item_image_gallery['item_image_gallery_name'] = 'gallery-'.$currentDate.'-'.$item_image_gallery_uniqid.'.jpg';
                    $item_image_gallery['item_image_gallery_thumb_name'] = 'gallery-'.$currentDate.'-'.$item_image_gallery_uniqid.'-thumb.jpg';

                    if(!Storage::disk('public')->exists('item/gallery')){
                        Storage::disk('public')->makeDirectory('item/gallery');
                    }

                    // original
                    $one_gallery_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))->stream('jpg', 80);
                    Storage::disk('public')->put('item/gallery/'.$item_image_gallery['item_image_gallery_name'], $one_gallery_image);

                    // thumb size
                    $one_gallery_image_thumb = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))
                        ->resize(null, 180, function($constraint) {
                            $constraint->aspectRatio();
                        });
                    $one_gallery_image_thumb = $one_gallery_image_thumb->stream('jpg', 70);
                    Storage::disk('public')->put('item/gallery/'.$item_image_gallery['item_image_gallery_thumb_name'], $one_gallery_image_thumb);

                    $created_item_image_gallery = $item->galleries()->create($item_image_gallery);
                }
            }
        }

        /**
         * Start save item hours
         */
        $item_hours = empty($request->item_hours) ? array() : $request->item_hours;

        foreach($item_hours as $item_hours_key => $item_hour)
        {
            $item_hour_record = explode(' ', $item_hour);

            if(count($item_hour_record) == 3)
            {
                $item_hour_day_of_week = intval($item_hour_record[0]);

                if($item_hour_day_of_week >= ItemHour::DAY_OF_WEEK_MONDAY && $item_hour_day_of_week <= ItemHour::DAY_OF_WEEK_SUNDAY)
                {
                    $item_hour_open_hour = intval(substr($item_hour_record[1], 0, 2));
                    $item_hour_close_hour = intval(substr($item_hour_record[2], 0, 2));

                    if($item_hour_open_hour <= $item_hour_close_hour)
                    {
                        $item_hour_open_time = $item_hour_record[1] . ':00';
                        $item_hour_close_time = $item_hour_record[2] . ':00';

                        if($item_hour_open_hour == 24)
                        {
                            $item_hour_open_time = '24:00:00';
                        }

                        if($item_hour_close_hour == 24)
                        {
                            $item_hour_close_time = '24:00:00';
                        }

                        if($item_hour_open_time != $item_hour_close_time)
                        {
                            $create_item_hour = new ItemHour(array(
                                'item_id' => $item->id,
                                'item_hour_day_of_week' => $item_hour_day_of_week,
                                'item_hour_open_time' => $item_hour_open_time,
                                'item_hour_close_time' => $item_hour_close_time,
                            ));
                            $create_item_hour->save();
                        }
                    }
                }
            }
        }
        /**
         * End save item hours
         */

        /**
         * Start save item hour exceptions
         */
        $item_hour_exceptions = empty($request->item_hour_exceptions) ? array() : $request->item_hour_exceptions;

        foreach($item_hour_exceptions as $item_hour_exceptions_key => $item_hour_exception)
        {
            $item_hour_exception_record = explode(' ', $item_hour_exception);

            $item_hour_exception_date = $item_hour_exception_record[0];

            if(DateTime::createFromFormat('Y-m-d', $item_hour_exception_date) !== false)
            {
                $item_hour_exception_open_time = null;
                $item_hour_exception_close_time = null;

                if(count($item_hour_exception_record) == 3)
                {
                    $item_hour_exception_open_time = $item_hour_exception_record[1] . ':00';
                    $item_hour_exception_close_time = $item_hour_exception_record[2] . ':00';

                    $item_hour_exception_open_hour = intval(substr($item_hour_exception_record[1], 0, 2));
                    $item_hour_exception_close_hour = intval(substr($item_hour_exception_record[2], 0, 2));

                    if($item_hour_exception_open_hour == 24)
                    {
                        $item_hour_exception_open_time = '24:00:00';
                    }

                    if($item_hour_exception_close_hour == 24)
                    {
                        $item_hour_exception_close_time = '24:00:00';
                    }

                    if($item_hour_exception_open_hour > $item_hour_exception_close_hour || $item_hour_exception_open_time == $item_hour_exception_close_time)
                    {
                        continue;
                    }
                }

                $create_item_hour_exception = new ItemHourException(array(
                    'item_id' => $item->id,
                    'item_hour_exception_date' => $item_hour_exception_date,
                    'item_hour_exception_open_time' => $item_hour_exception_open_time,
                    'item_hour_exception_close_time' => $item_hour_exception_close_time,
                ));
                $create_item_hour_exception->save();
            }
        }
        /**
         * End save item hour exceptions
         */

        // success, flash message
        \Session::flash('flash_message', __('alert.item-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.edit', ['item' => $item]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return RedirectResponse
     */
    public function destroy(Item $item)
    {
        Gate::authorize('delete-item', $item);

        $item->deleteItem();

        \Session::flash('flash_message', __('alert.item-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.index');
    }

    public function savedItems()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        //SEOMeta::setTitle('Dashboard - Saved Listings - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.user.item.saved-items', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $login_user = Auth::user();

        $saved_items = $login_user->savedItems()->get();

        return response()->view('backend.user.item.saved',
            compact('saved_items'));
    }

    public function unSaveItem(Request $request, string $item_slug)
    {
        $settings = app('site_global_settings');
        //$site_prefer_country_id = app('site_prefer_country_id');

        $item = Item::where('item_slug', $item_slug)
            //->where('country_id', $site_prefer_country_id)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->first();

        if($item)
        {
            $login_user = Auth::user();

            if($login_user->hasSavedItem($item->id))
            {
                $login_user->savedItems()->detach($item->id);

                \Session::flash('flash_message', __('backend.item.unsave-item-success'));
                \Session::flash('flash_type', 'success');

                return redirect()->route('user.items.saved');
            }
            else
            {
                \Session::flash('flash_message', __('backend.item.unsave-item-error-exist'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('user.items.saved');
            }
        }
        else
        {
            abort(404);
        }

    }



    /**
     * @param string $item_slug
     * @return Response
     */
    public function itemReviewsCreate(string $item_slug)
    {
        $settings = app('site_global_settings');
        //$site_prefer_country_id = app('site_prefer_country_id');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('review.seo.write-a-review', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $item = Item::where('item_slug', $item_slug)
            //->where('country_id', $site_prefer_country_id)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->where('user_id', '!=', \Illuminate\Support\Facades\Auth::user()->id)
            ->first();

        if($item)
        {
            if($item->reviewedByUser(\Illuminate\Support\Facades\Auth::user()->id))
            {
                \Session::flash('flash_message', __('review.alert.cannot-post-more-one-review'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('page.item', $item->item_slug);
            }
            else
            {
                return response()->view('backend.user.item.review.create',
                    compact('item'));
            }

        }
        else
        {
            abort(404);
        }
    }

    public function itemReviewsStore(Request $request, string $item_slug)
    {
        $settings = app('site_global_settings');
        //$site_prefer_country_id = app('site_prefer_country_id');

        $item = Item::where('item_slug', $item_slug)
            //->where('country_id', $site_prefer_country_id)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->where('user_id', '!=', \Illuminate\Support\Facades\Auth::user()->id)
            ->first();

        if($item)
        {
            if($item->reviewedByUser(Auth::user()->id))
            {
                \Session::flash('flash_message', __('review.alert.cannot-post-more-one-review'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('page.item', $item->item_slug);
            }
            else
            {
                $request->validate([
                    'rating' => 'required|numeric|max:5',
                    'customer_service_rating' => 'required|numeric|max:5',
                    'quality_rating' => 'required|numeric|max:5',
                    'friendly_rating' => 'required|numeric|max:5',
                    'pricing_rating' => 'required|numeric|max:5',
                    'title' => 'nullable|max:255',
                    'body' => 'required|max:65535',
                    'recommend' => 'nullable|numeric|max:1',
                ]);

                $login_user = Auth::user();
                $rating_title = empty($request->title) ? '' : $request->title;
                $rating_body = $request->body;
                $overall_rating = $request->rating;
                $customer_service_rating = $request->customer_service_rating;
                $quality_rating = $request->quality_rating;
                $friendly_rating = $request->friendly_rating;
                $pricing_rating = $request->pricing_rating;
                $recommend = $request->recommend == 1 ? Item::ITEM_REVIEW_RECOMMEND_YES : Item::ITEM_REVIEW_RECOMMEND_NO;
                $approved = $login_user->isAdmin() ? true : false;

                $new_rating = $item->rating([
                    'title' => $rating_title,
                    'body' => $rating_body,
                    'customer_service_rating' => $customer_service_rating,
                    'quality_rating' => $quality_rating,
                    'friendly_rating' => $friendly_rating,
                    'pricing_rating' => $pricing_rating,
                    'rating' => $overall_rating,
                    'recommend' => $recommend,
                    'approved' => $approved, // This is optional and defaults to false
                ], $login_user);

                // start to upload image galleries
                $image_gallary = $request->review_image_galleries;
                if(is_array($image_gallary) && count($image_gallary) > 0)
                {
                    foreach($image_gallary as $key => $image)
                    {
                        // only total 12 images are allowed
                        if($key < 12)
                        {
                            $currentDate = Carbon::now()->toDateString();
                            $review_image_gallery_uniqid = uniqid();

                            $review_image_gallery['review_image_gallery_name'] = 'review-gallary-'.$currentDate.'-'.$review_image_gallery_uniqid.'.jpg';
                            $review_image_gallery['review_image_gallery_thumb_name'] = 'review-gallary-'.$currentDate.'-'.$review_image_gallery_uniqid.'-thumb.jpg';

                            if(!Storage::disk('public')->exists('item/review')){
                                Storage::disk('public')->makeDirectory('item/review');
                            }

                            // original
                            $one_gallery_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))->stream('jpg', 80);
                            Storage::disk('public')->put('item/review/'.$review_image_gallery['review_image_gallery_name'], $one_gallery_image);

                            // thumb size
                            $one_gallery_image_thumb = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))
                                ->resize(150, null, function($constraint) {
                                    $constraint->aspectRatio();
                                });
                            $one_gallery_image_thumb = $one_gallery_image_thumb->stream('jpg', 70);
                            Storage::disk('public')->put('item/review/'.$review_image_gallery['review_image_gallery_thumb_name'], $one_gallery_image_thumb);

                            // insert review image galleries record to review_image_galleries table
                            $item->insertReviewGalleriesByReviewId($new_rating->id,
                                $review_image_gallery['review_image_gallery_name'],
                                $review_image_gallery['review_image_gallery_thumb_name']);
                        }
                    }
                }

                \Session::flash('flash_message', __('review.alert.review-posted-success'));
                \Session::flash('flash_type', 'success');

                return redirect()->route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $new_rating->id]);
            }

        }
        else
        {
            abort(404);
        }
    }

    public function itemReviewsEdit(string $item_slug, int $review)
    {
        $settings = app('site_global_settings');
        //$site_prefer_country_id = app('site_prefer_country_id');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('review.seo.edit-a-review', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $item = Item::where('item_slug', $item_slug)
            //->where('country_id', $site_prefer_country_id)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->where('user_id', '!=', \Illuminate\Support\Facades\Auth::user()->id)
            ->first();

        if($item)
        {
            if($item->hasReviewByIdAndUser($review, Auth::user()->id))
            {
                $review = $item->getReviewById($review);

                $review_image_galleries = $item->getReviewGalleriesByReviewId($review->id);

                return response()->view('backend.user.item.review.edit',
                    compact('item', 'review', 'review_image_galleries'));
            }
            else
            {
                abort(404);
            }
        }
        else
        {
            abort(404);
        }
    }

    public function itemReviewsUpdate(Request $request, string $item_slug, int $review)
    {
        $settings = app('site_global_settings');
        //$site_prefer_country_id = app('site_prefer_country_id');

        $item = Item::where('item_slug', $item_slug)
            //->where('country_id', $site_prefer_country_id)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->where('user_id', '!=', \Illuminate\Support\Facades\Auth::user()->id)
            ->first();

        if($item)
        {
            if($item->hasReviewByIdAndUser($review, Auth::user()->id))
            {
                $request->validate([
                    'rating' => 'required|numeric|max:5',
                    'customer_service_rating' => 'required|numeric|max:5',
                    'quality_rating' => 'required|numeric|max:5',
                    'friendly_rating' => 'required|numeric|max:5',
                    'pricing_rating' => 'required|numeric|max:5',
                    'title' => 'nullable|max:255',
                    'body' => 'required|max:65535',
                    'recommend' => 'nullable|numeric|max:1',
                ]);

                $login_user = Auth::user();
                $rating_title = empty($request->title) ? '' : $request->title;
                $rating_body = $request->body;
                $overall_rating = $request->rating;
                $customer_service_rating = $request->customer_service_rating;
                $quality_rating = $request->quality_rating;
                $friendly_rating = $request->friendly_rating;
                $pricing_rating = $request->pricing_rating;
                $recommend = $request->recommend == 1 ? Item::ITEM_REVIEW_RECOMMEND_YES : Item::ITEM_REVIEW_RECOMMEND_NO;
                $approved = $login_user->isAdmin() ? true : false;

                $updated_rating = $item->updateRating($review, [
                    'title' => $rating_title,
                    'body' => $rating_body,
                    'rating' => $overall_rating,
                    'customer_service_rating' => $customer_service_rating,
                    'quality_rating' => $quality_rating,
                    'friendly_rating' => $friendly_rating,
                    'pricing_rating' => $pricing_rating,
                    'recommend' => $recommend,
                    'approved' => $approved, // This is optional and defaults to false
                ]);

                // start to upload image galleries
                $image_gallary = $request->review_image_galleries;
                if(is_array($image_gallary) && count($image_gallary) > 0)
                {
                    $total_review_image_gallery = $item->reviewGalleryCountByReviewId($review);
                    foreach($image_gallary as $key => $image)
                    {
                        // only total 12 images are allowed
                        if($total_review_image_gallery + $key < 12)
                        {
                            $currentDate = Carbon::now()->toDateString();
                            $review_image_gallery_uniqid = uniqid();

                            $review_image_gallery['review_image_gallery_name'] = 'review-gallary-'.$currentDate.'-'.$review_image_gallery_uniqid.'.jpg';
                            $review_image_gallery['review_image_gallery_thumb_name'] = 'review-gallary-'.$currentDate.'-'.$review_image_gallery_uniqid.'-thumb.jpg';

                            if(!Storage::disk('public')->exists('item/review')){
                                Storage::disk('public')->makeDirectory('item/review');
                            }

                            // original
                            $one_gallery_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))->stream('jpg', 80);
                            Storage::disk('public')->put('item/review/'.$review_image_gallery['review_image_gallery_name'], $one_gallery_image);

                            // thumb size
                            $one_gallery_image_thumb = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))
                                ->resize(150, null, function($constraint) {
                                    $constraint->aspectRatio();
                                });
                            $one_gallery_image_thumb = $one_gallery_image_thumb->stream('jpg', 70);
                            Storage::disk('public')->put('item/review/'.$review_image_gallery['review_image_gallery_thumb_name'], $one_gallery_image_thumb);

                            // insert review image galleries record to review_image_galleries table
                            $item->insertReviewGalleriesByReviewId($updated_rating->id,
                                $review_image_gallery['review_image_gallery_name'],
                                $review_image_gallery['review_image_gallery_thumb_name']);
                        }
                    }
                }

                \Session::flash('flash_message', __('review.alert.review-updated-success'));
                \Session::flash('flash_type', 'success');

                return redirect()->route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $updated_rating->id]);
            }
            else
            {
                abort(404);
            }
        }
        else
        {
            abort(404);
        }
    }

    public function itemReviewsDestroy(string $item_slug, int $review)
    {
        $settings = app('site_global_settings');
        //$site_prefer_country_id = app('site_prefer_country_id');

        $item = Item::where('item_slug', $item_slug)
            //->where('country_id', $site_prefer_country_id)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->where('user_id', '!=', \Illuminate\Support\Facades\Auth::user()->id)
            ->first();

        if($item)
        {
            if($item->hasReviewByIdAndUser($review, Auth::user()->id))
            {
                $item->deleteRating($review);

                // sync item_average_rating in item table
                $item->syncItemAverageRating();

                \Session::flash('flash_message', __('review.alert.review-deleted-success'));
                \Session::flash('flash_type', 'success');

                return redirect()->route('user.items.reviews.index');
            }
            else
            {
                abort(404);
            }
        }
        else
        {
            abort(404);
        }
    }

    public function itemReviewsIndex(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('review.seo.manage-reviews', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $reviews_type = $request->reviews_type;

        if(empty($reviews_type) || $reviews_type == 'all')
        {
            $reviews = DB::table('reviews')
                ->where('author_id', \Illuminate\Support\Facades\Auth::user()->id)
                ->orderBy('updated_at', 'desc')
                ->get();
        }
        else
        {
            if($reviews_type == 'pending')
            {
                $reviews = DB::table('reviews')
                    ->where('author_id', \Illuminate\Support\Facades\Auth::user()->id)
                    ->where('approved', Item::ITEM_REVIEW_PENDING)
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }

            if($reviews_type == 'approved')
            {
                $reviews = DB::table('reviews')
                    ->where('author_id', \Illuminate\Support\Facades\Auth::user()->id)
                    ->where('approved', Item::ITEM_REVIEW_APPROVED)
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }
        }

        return response()->view('backend.user.item.review.index',
            compact('reviews_type', 'reviews'));
    }



    public function indexItemSections(Request $request, Item $item)
    {
        Gate::authorize('update-item', $item);

        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('item_section.seo.index', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $item_sections_after_breadcrumb = $item->itemSections()
            ->where('item_section_position', ItemSection::POSITION_AFTER_BREADCRUMB)
            ->orderBy('item_section_order')
            ->get();

        $item_section_after_gallery = $item->itemSections()
            ->where('item_section_position', ItemSection::POSITION_AFTER_GALLERY)
            ->orderBy('item_section_order')
            ->get();

        $item_section_after_description = $item->itemSections()
            ->where('item_section_position', ItemSection::POSITION_AFTER_DESCRIPTION)
            ->orderBy('item_section_order')
            ->get();

        $item_section_after_location_map = $item->itemSections()
            ->where('item_section_position', ItemSection::POSITION_AFTER_LOCATION_MAP)
            ->orderBy('item_section_order')
            ->get();

        $item_section_after_features = $item->itemSections()
            ->where('item_section_position', ItemSection::POSITION_AFTER_FEATURES)
            ->orderBy('item_section_order')
            ->get();

        $item_section_after_reviews = $item->itemSections()
            ->where('item_section_position', ItemSection::POSITION_AFTER_REVIEWS)
            ->orderBy('item_section_order')
            ->get();

        $item_section_after_comments = $item->itemSections()
            ->where('item_section_position', ItemSection::POSITION_AFTER_COMMENTS)
            ->orderBy('item_section_order')
            ->get();

        $item_section_after_share = $item->itemSections()
            ->where('item_section_position', ItemSection::POSITION_AFTER_SHARE)
            ->orderBy('item_section_order')
            ->get();

        $item_has_claimed = $item->hasClaimed();
        $item_claimed_user = $item->getClaimedUser();

        return response()->view('backend.user.item.item-section.index',
            compact('item', 'item_sections_after_breadcrumb', 'item_section_after_gallery',
                'item_section_after_description', 'item_section_after_location_map', 'item_section_after_features',
                'item_section_after_reviews', 'item_section_after_comments', 'item_section_after_share',
                'item_has_claimed', 'item_claimed_user'));
    }

    public function storeItemSection(Request $request, Item $item)
    {
        Gate::authorize('update-item', $item);

        /**
         * Start form validation
         */
        $request->validate([
            'item_section_title' => 'required|max:255',
            'item_section_position' => 'required|numeric|in:1,2,3,4,5,6,7,8',
            'item_section_status' => 'required|numeric|in:1,2',
        ]);
        /**
         * End form validation
         */

        $item_section_title = $request->item_section_title;
        $item_section_position = $request->item_section_position;
        $item_section_status = $request->item_section_status == ItemSection::STATUS_DRAFT ? ItemSection::STATUS_DRAFT : ItemSection::STATUS_PUBLISHED;
        $item_section_order = $item->itemSections()->where('item_section_position', $item_section_position)->count() + 1;

        $new_item_section = $item->itemSections()->create(array(
            'item_section_title' => $item_section_title,
            'item_section_position' => $item_section_position,
            'item_section_order' => $item_section_order,
            'item_section_status' => $item_section_status,
        ));

        \Session::flash('flash_message', __('item_section.alert.item-section-created'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.sections.edit', ['item' => $item, 'item_section' => $new_item_section->id]);
    }

    public function editItemSection(Request $request, Item $item, ItemSection $item_section)
    {
        Gate::authorize('update-item', $item);

        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('item_section.seo.edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        // Validate item and item_section relation
        if($item_section->item_id != $item->id)
        {
            \Session::flash('flash_message', __('item_section.alert.item-section-not-match-item'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.items.sections.index', ['item' => $item]);
        }
        else
        {
            $all_item_section_collections = $item_section->itemSectionCollections()
                ->orderBy('item_section_collection_order')
                ->get();

            $item_has_claimed = $item->hasClaimed();
            $item_claimed_user = $item->getClaimedUser();

            /**
             * Start initial available collections
             */
            // Collected products
            $item_section_collection_collected_products = $item_section->itemSectionCollections()
                ->where('item_section_collection_collectible_type', ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                ->get();

            $collected_product_ids = array();
            foreach($item_section_collection_collected_products as $key => $item_section_collection_collected_product)
            {
                $collected_product_ids[] = $item_section_collection_collected_product->item_section_collection_collectible_id;
            }

            // Available products
            $available_products = Product::where('user_id', $item->user_id)
                ->whereNotIn('id', $collected_product_ids)
                ->get();

            $product_currency_symbol = $settings->setting_product_currency_symbol;
            /**
             * End initial available collections
             */

            return response()->view('backend.user.item.item-section.edit',
                compact('item', 'item_section', 'all_item_section_collections',
                    'item_has_claimed', 'item_claimed_user', 'available_products', 'product_currency_symbol'));
        }
    }

    public function updateItemSection(Request $request, Item $item, ItemSection $item_section)
    {
        Gate::authorize('update-item', $item);

        // Validate item and item_section relation
        if($item_section->item_id != $item->id)
        {
            \Session::flash('flash_message', __('item_section.alert.item-section-not-match-item'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.items.sections.index', ['item' => $item]);
        }

        /**
         * Start form validation
         */
        $request->validate([
            'item_section_title' => 'required|max:255',
            'item_section_position' => 'required|numeric|in:1,2,3,4,5,6,7,8',
            'item_section_status' => 'required|numeric|in:1,2',
        ]);
        /**
         * End form validation
         */

        $item_section_title = $request->item_section_title;
        $item_section_position = $request->item_section_position;
        $item_section_status = $request->item_section_status;

        $item_section_order = $item_section->item_section_order;

        // check if position has updated
        if($item_section->item_section_position != $item_section_position)
        {
            // the item section has updated to a new position, so we need to
            // update the item_section_order based on the new position
            $item_section_order = $item->itemSections()->where('item_section_position', $item_section_position)->count() + 1;

            // also, the old position need to re-order
            $re_order_item_sections = $item->itemSections()
                ->where('item_section_position', $item_section->item_section_position)
                ->where('item_section_order', '>', $item_section->item_section_order)
                ->get();

            foreach($re_order_item_sections as $key => $re_order_item_section)
            {
                $re_order_item_section->item_section_order = $re_order_item_section->item_section_order - 1;
                $re_order_item_section->save();
            }

        }

        $item_section->item_section_title = $item_section_title;
        $item_section->item_section_position = $item_section_position;
        $item_section->item_section_order = $item_section_order;
        $item_section->item_section_status = $item_section_status;
        $item_section->save();

        \Session::flash('flash_message', __('item_section.alert.item-section-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.sections.edit', ['item' => $item, 'item_section' => $item_section->id]);
    }


    public function destroyItemSection(Request $request, Item $item, ItemSection $item_section)
    {
        Gate::authorize('update-item', $item);

        // Validate item and item_section relation
        if($item_section->item_id != $item->id)
        {
            \Session::flash('flash_message', __('item_section.alert.item-section-not-match-item'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.items.sections.index', ['item' => $item]);
        }

        // #1 - delete all item_section's collections
        $all_item_section_collections = $item_section->itemSectionCollections()->get();

        foreach($all_item_section_collections as $key => $item_section_collection)
        {
            $item_section_collection->delete();
        }

        // #2 - re-order item_section_order
        $item_sections = $item->itemSections()
            ->where('item_section_position', $item_section->item_section_position)
            ->where('item_section_order', '>', $item_section->item_section_order)
            ->orderBy('item_section_order')
            ->get();

        foreach($item_sections as $key => $section)
        {
            $section->item_section_order = intval($section->item_section_order) - 1;
            $section->save();
        }

        // #3 - delete $item_section
        $item_section->delete();

        \Session::flash('flash_message', __('item_section.alert.item-section-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.sections.index', ['item' => $item]);
    }

    public function rankUpItemSection(Request $request, Item $item, ItemSection $item_section)
    {
        Gate::authorize('update-item', $item);

        // Validate item and item_section relation
        if($item_section->item_id != $item->id)
        {
            \Session::flash('flash_message', __('item_section.alert.item-section-not-match-item'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.items.sections.index', ['item' => $item]);
        }

        $old_rank = intval($item_section->item_section_order);

        $move_down_item_section = $item->itemSections()
            ->where('item_section_position', $item_section->item_section_position)
            ->where('item_section_order', $old_rank-1)
            ->get();

        if($move_down_item_section->count() > 0)
        {
            $move_down_item_section = $move_down_item_section->first();
            $move_down_item_section->item_section_order = $old_rank;
            $move_down_item_section->save();

            $item_section->item_section_order = $old_rank-1;
            $item_section->save();
        }

        \Session::flash('flash_message', __('item_section.alert.item-section-ranked-up'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.sections.index', ['item' => $item]);
    }

    public function rankDownItemSection(Request $request, Item $item, ItemSection $item_section)
    {
        Gate::authorize('update-item', $item);

        // Validate item and item_section relation
        if($item_section->item_id != $item->id)
        {
            \Session::flash('flash_message', __('item_section.alert.item-section-not-match-item'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.items.sections.index', ['item' => $item]);
        }

        $old_rank = intval($item_section->item_section_order);

        $move_up_item_section = $item->itemSections()
            ->where('item_section_position', $item_section->item_section_position)
            ->where('item_section_order', $old_rank+1)
            ->get();

        if($move_up_item_section->count() > 0)
        {
            $move_up_item_section = $move_up_item_section->first();
            $move_up_item_section->item_section_order = $old_rank;
            $move_up_item_section->save();

            $item_section->item_section_order = $old_rank+1;
            $item_section->save();
        }

        \Session::flash('flash_message', __('item_section.alert.item-section-ranked-down'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.sections.index', ['item' => $item]);
    }


    public function storeItemSectionCollections(Request $request, Item $item, ItemSection $item_section)
    {
        Gate::authorize('update-item', $item);

        // Validate item and item_section relation
        if($item_section->item_id != $item->id)
        {
            \Session::flash('flash_message', __('item_section.alert.item-section-not-match-item'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.items.sections.index', ['item' => $item]);
        }

        /**
         * Start form validation
         */
        $request->validate([
            'item_section_collection_collectible_type' => 'required|max:255',
            'item_section_collection_collectible_id' => 'required|array',
            'item_section_collection_collectible_id.*' => 'required|numeric',
        ]);

        $item_section_collection_collectible_type = $request->item_section_collection_collectible_type;
        $item_section_collection_collectible_ids = $request->item_section_collection_collectible_id;

        $validate_error = array();

        $available_item_section_collection_collectible_types = array(
            ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT,
        );
        if(!in_array($item_section_collection_collectible_type, $available_item_section_collection_collectible_types))
        {
            $validate_error['item_section_collection_collectible_id'] = __('item_section.alert.item-section-collection-collectible-type-not-exist');
        }

        foreach($item_section_collection_collectible_ids as $key => $item_section_collection_collectible_id)
        {
            if($item_section_collection_collectible_type == ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
            {
                $product_exist = Product::find($item_section_collection_collectible_id);
                if(!$product_exist)
                {
                    $validate_error['item_section_collection_collectible_id'] = __('item_section.alert.product-not-exist');
                }
                else
                {
                    if($product_exist->user_id != $item->user_id)
                    {
                        $validate_error['item_section_collection_collectible_id'] = __('item_section.alert.not-product-owner');
                    }
                }
            }
        }
        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }
        /**
         * End form validation
         */

        /**
         * Start saving collections to the item_section_collections table
         */
        $item_section_collections_count = $item_section->itemSectionCollections()->count();

        foreach($item_section_collection_collectible_ids as $key => $collection_id)
        {
            $new_item_section_collection = new ItemSectionCollection(array(
                'item_section_collection_order' => $item_section_collections_count + ($key +1),
                'item_section_collection_collectible_type' => $item_section_collection_collectible_type,
                'item_section_collection_collectible_id' => $collection_id,
            ));

            $item_section->itemSectionCollections()->save($new_item_section_collection);
        }
        /**
         * End saving collections to the item_section_collections table
         */

        \Session::flash('flash_message', __('item_section.alert.item-section-collection-product-added'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.sections.edit', ['item' => $item, 'item_section' => $item_section->id]);
    }


    public function rankUpItemSectionCollection(Request $request,
                                                Item $item,
                                                ItemSection $item_section,
                                                ItemSectionCollection $item_section_collection)
    {
        Gate::authorize('update-item', $item);

        // Validate item and item_section relation
        if($item_section->item_id != $item->id)
        {
            \Session::flash('flash_message', __('item_section.alert.item-section-not-match-item'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.items.sections.index', ['item' => $item]);
        }

        // Validate item_section and item_section_collection relation
        if($item_section_collection->item_section_id != $item_section->id)
        {
            \Session::flash('flash_message', __('item_section.alert.item-section-collection-not-match-item-section'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.items.sections.edit', ['item' => $item, 'item_section' => $item_section]);
        }

        $old_rank = intval($item_section_collection->item_section_collection_order);

        $move_down_item_section_collection = $item_section->itemSectionCollections()
            ->where('item_section_collection_order', $old_rank-1)
            ->get();

        if($move_down_item_section_collection->count() > 0)
        {
            $move_down_item_section_collection = $move_down_item_section_collection->first();
            $move_down_item_section_collection->item_section_collection_order = $old_rank;
            $move_down_item_section_collection->save();

            $item_section_collection->item_section_collection_order = $old_rank-1;
            $item_section_collection->save();
        }

        \Session::flash('flash_message', __('item_section.alert.item-section-collection-ranked-up'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.sections.edit', ['item' => $item, 'item_section' => $item_section]);
    }


    public function rankDownItemSectionCollection(Request $request,
                                                  Item $item,
                                                  ItemSection $item_section,
                                                  ItemSectionCollection $item_section_collection)
    {
        Gate::authorize('update-item', $item);

        // Validate item and item_section relation
        if($item_section->item_id != $item->id)
        {
            \Session::flash('flash_message', __('item_section.alert.item-section-not-match-item'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.items.sections.index', ['item' => $item]);
        }

        // Validate item_section and item_section_collection relation
        if($item_section_collection->item_section_id != $item_section->id)
        {
            \Session::flash('flash_message', __('item_section.alert.item-section-collection-not-match-item-section'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.items.sections.edit', ['item' => $item, 'item_section' => $item_section]);
        }

        $old_rank = intval($item_section_collection->item_section_collection_order);

        $move_up_item_section_collection = $item_section->itemSectionCollections()
            ->where('item_section_collection_order', $old_rank+1)
            ->get();

        if($move_up_item_section_collection->count() > 0)
        {
            $move_up_item_section_collection = $move_up_item_section_collection->first();
            $move_up_item_section_collection->item_section_collection_order = $old_rank;
            $move_up_item_section_collection->save();

            $item_section_collection->item_section_collection_order = $old_rank+1;
            $item_section_collection->save();
        }

        \Session::flash('flash_message', __('item_section.alert.item-section-collection-ranked-down'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.sections.edit', ['item' => $item, 'item_section' => $item_section]);
    }


    public function destroyItemSectionCollection(Request $request,
                                                 Item $item,
                                                 ItemSection $item_section,
                                                 ItemSectionCollection $item_section_collection)
    {
        Gate::authorize('update-item', $item);

        // Validate item and item_section relation
        if($item_section->item_id != $item->id)
        {
            \Session::flash('flash_message', __('item_section.alert.item-section-not-match-item'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.items.sections.index', ['item' => $item]);
        }

        // Validate item_section and item_section_collection relation
        if($item_section_collection->item_section_id != $item_section->id)
        {
            \Session::flash('flash_message', __('item_section.alert.item-section-collection-not-match-item-section'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.items.sections.edit', ['item' => $item, 'item_section' => $item_section]);
        }

        // #1 - re-order the collections
        $re_order_item_section_collections = $item_section->itemSectionCollections()
            ->where('item_section_collection_order', '>', $item_section_collection->item_section_collection_order)
            ->get();

        foreach($re_order_item_section_collections as $key => $collection)
        {
            $collection->item_section_collection_order = $collection->item_section_collection_order - 1;
            $collection->save();
        }

        // #2 - delete record in item_section_collections table
        $item_section_collection->delete();

        \Session::flash('flash_message', __('item_section.alert.item-section-collection-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.sections.edit', ['item' => $item, 'item_section' => $item_section]);
    }

    public function bulkDeleteItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|array',
        ]);

        $item_ids = $request->item_id;
        $login_user = Auth::user();

        foreach($item_ids as $item_ids_key => $a_item_id)
        {
            $item = Item::find($a_item_id);

            if($item && $item->user_id == $login_user->id)
            {
                $item->deleteItem();
            }
        }

        \Session::flash('flash_message', __('item_index.alert.bulk-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.items.index', $request->query());
    }

    public function destroyItemHour(Request $request, ItemHour $item_hour)
    {
        $login_user = Auth::user();

        $item = $item_hour->item()->first();

        if($item && $item->user_id == $login_user->id)
        {
            $item_hour->delete();

            \Session::flash('flash_message', __('item_hour.alert.item-hour-deleted'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('user.items.edit', ['item' => $item]);
        }
        else
        {
            return redirect()->route('user.items.index');
        }
    }

    public function destroyItemHourException(Request $request, ItemHourException $item_hour_exception)
    {
        $login_user = Auth::user();

        $item = $item_hour_exception->item()->first();

        if($item && $item->user_id == $login_user->id)
        {
            $item_hour_exception->delete();

            \Session::flash('flash_message', __('item_hour.alert.item-hour-exception-deleted'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('user.items.edit', ['item' => $item]);
        }
        else
        {
            return redirect()->route('user.items.index');
        }
    }

    public function updateItemHour(Request $request, ItemHour $item_hour)
    {
        $login_user = Auth::user();

        $item = $item_hour->item()->first();

        if($item && $item->user_id == $login_user->id)
        {
            $request->validate([
                'item_hour_day_of_week' => 'required|numeric|between:1,7',
                'item_hour_open_time_hour' => 'required|numeric|between:0,24',
                'item_hour_open_time_minute' => 'required|numeric|between:0,59',
                'item_hour_close_time_hour' => 'required|numeric|between:0,24',
                'item_hour_close_time_minute' => 'required|numeric|between:0,59',
            ]);

            $item_hour_day_of_week = $request->item_hour_day_of_week;

            $item_hour_open_time_hour = intval($request->item_hour_open_time_hour);
            $item_hour_close_time_hour = intval($request->item_hour_close_time_hour);

            if($item_hour_open_time_hour <= $item_hour_close_time_hour)
            {
                $item_hour_open_time = $request->item_hour_open_time_hour . ':' . $request->item_hour_open_time_minute . ':00';
                $item_hour_close_time = $request->item_hour_close_time_hour . ':' . $request->item_hour_close_time_minute . ':00';

                if($item_hour_open_time_hour == 24)
                {
                    $item_hour_open_time = '24:00:00';
                }

                if($item_hour_close_time_hour == 24)
                {
                    $item_hour_close_time = '24:00:00';
                }

                if($item_hour_open_time != $item_hour_close_time)
                {
                    $item_hour->item_hour_day_of_week = $item_hour_day_of_week;
                    $item_hour->item_hour_open_time = $item_hour_open_time;
                    $item_hour->item_hour_close_time = $item_hour_close_time;
                    $item_hour->save();
                }
            }

            \Session::flash('flash_message', __('item_hour.alert.item-hour-updated'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('user.items.edit', ['item' => $item]);
        }
        else
        {
            return redirect()->route('user.items.index');
        }
    }

    public function updateItemHourException(Request $request, ItemHourException $item_hour_exception)
    {
        $login_user = Auth::user();

        $item = $item_hour_exception->item()->first();

        if($item && $item->user_id == $login_user->id)
        {
            $request->validate([
                'item_hour_exception_date' => 'required|date|date_format:Y-m-d',
                'item_hour_exception_open_time_hour' => 'nullable|numeric|between:0,24',
                'item_hour_exception_open_time_minute' => 'nullable|numeric|between:0,59',
                'item_hour_exception_close_time_hour' => 'nullable|numeric|between:0,24',
                'item_hour_exception_close_time_minute' => 'nullable|numeric|between:0,59',
            ]);

            $item_hour_exception_date = $request->item_hour_exception_date;
            $item_hour_exception_open_time_hour = is_null($request->item_hour_exception_open_time_hour) ? null : $request->item_hour_exception_open_time_hour;
            $item_hour_exception_open_time_minute = is_null($request->item_hour_exception_open_time_minute) ? null : $request->item_hour_exception_open_time_minute;
            $item_hour_exception_close_time_hour = is_null($request->item_hour_exception_close_time_hour) ? null : $request->item_hour_exception_close_time_hour;
            $item_hour_exception_close_time_minute = is_null($request->item_hour_exception_close_time_minute) ? null : $request->item_hour_exception_close_time_minute;

            $item_hour_exception_open_time = null;
            $item_hour_exception_close_time = null;

            $can_be_updated = true;

            if(!is_null($item_hour_exception_open_time_hour) && !is_null($item_hour_exception_open_time_minute) && !is_null($item_hour_exception_close_time_hour) && !is_null($item_hour_exception_close_time_minute))
            {
                $item_hour_exception_open_time = $item_hour_exception_open_time_hour . ':' . $item_hour_exception_open_time_minute . ':00';
                $item_hour_exception_close_time = $item_hour_exception_close_time_hour . ':' . $item_hour_exception_close_time_minute . ':00';

                if(intval($item_hour_exception_open_time_hour) == 24)
                {
                    $item_hour_exception_open_time = '24:00:00';
                }

                if(intval($item_hour_exception_close_time_hour) == 24)
                {
                    $item_hour_exception_close_time = '24:00:00';
                }

                if(intval($item_hour_exception_open_time_hour) > intval($item_hour_exception_close_time_hour) || $item_hour_exception_open_time == $item_hour_exception_close_time)
                {
                    $can_be_updated = false;
                }
            }

            if($can_be_updated)
            {
                $item_hour_exception->item_hour_exception_date = $item_hour_exception_date;
                $item_hour_exception->item_hour_exception_open_time = $item_hour_exception_open_time;
                $item_hour_exception->item_hour_exception_close_time = $item_hour_exception_close_time;
                $item_hour_exception->save();
            }

            \Session::flash('flash_message', __('item_hour.alert.item-hour-exception-updated'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('user.items.edit', ['item' => $item]);
        }
        else
        {
            return redirect()->route('user.items.index');
        }
    }
}

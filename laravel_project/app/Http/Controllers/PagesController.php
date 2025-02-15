<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\BlogPost;
use App\Category;
use App\Country;
use App\Customization;
use App\Faq;
use App\Item;
use App\ItemHour;
use App\ItemImageGallery;
use App\ItemLead;
use App\ItemSection;
use App\Mail\Notification;
use App\Plan;
use App\Product;
use App\ProductImageGallery;
use App\Setting;
use App\State;
use App\Subscription;
use App\Testimonial;
use App\Theme;
use App\User;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Artesaos\SEOTools\Facades\SEOMeta;

use Spatie\OpeningHours\OpeningHours;

class PagesController extends Controller
{
    public function index(Request $request)
    {
        $settings = app('site_global_settings');
        $site_prefer_country_id = app('site_prefer_country_id');

        /**
         * Start SEO
         */
        SEOMeta::setTitle($settings->setting_site_seo_home_title . ' - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setDescription($settings->setting_site_seo_home_description);
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);

        // OpenGraph
        OpenGraph::setTitle($settings->setting_site_seo_home_title . ' - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        OpenGraph::setDescription($settings->setting_site_seo_home_description);
        OpenGraph::setUrl(URL::current());
        if(empty($settings->setting_site_logo))
        {
            OpenGraph::addImage(asset('favicon-96x96.ico'));
        }
        else
        {
            OpenGraph::addImage(Storage::disk('public')->url('setting/' . $settings->setting_site_logo));
        }

        // Twitter
        TwitterCard::setTitle($settings->setting_site_seo_home_title . ' - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        /**
         * End SEO
         */

        $subscription_obj = new Subscription();

        /**
         * first 6 categories order by total listings
         */
        $active_user_ids = $subscription_obj->getActiveUserIds();
        $categories = Category::withCount(['allItems' => function ($query) use ($active_user_ids, $site_prefer_country_id) {
            $query->whereIn('items.user_id', $active_user_ids)
                ->where('items.item_status', Item::ITEM_PUBLISHED)
                ->where(function ($query) use ($site_prefer_country_id) {
                    $query->where('items.country_id', $site_prefer_country_id)
                        ->orWhereNull('items.country_id');
                });
            }])
            ->where('category_parent_id', null)
            ->orderBy('all_items_count', 'desc')->take(6)->get();

        /**
         * get first latest 6 paid listings
         */
        // paid listing
        $paid_items_query = Item::query();

        // get paid users id array
        $paid_user_ids = $subscription_obj->getPaidUserIds();

        $paid_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
            ->where(function ($query) use ($site_prefer_country_id) {
                $query->where('items.country_id', $site_prefer_country_id)
                    ->orWhereNull('items.country_id');
            })
            ->where('items.item_featured', Item::ITEM_FEATURED)
            ->where(function($query) use ($paid_user_ids) {

                $query->whereIn('items.user_id', $paid_user_ids)
                    ->orWhere('items.item_featured_by_admin', Item::ITEM_FEATURED_BY_ADMIN);
            });

        $paid_items_query->orderBy('items.created_at', 'DESC')->distinct('items.id');

        $paid_items = $paid_items_query->with('state')
            ->with('city')
            ->with('user')
            ->take(6)
            ->get();

        $paid_items = $paid_items->shuffle();

        /**
         * get nearest 9 popular items by device lat and lng
         */
        if(!empty(session('user_device_location_lat', '')) && !empty(session('user_device_location_lng', '')))
        {
            $latitude = session('user_device_location_lat', '');
            $longitude = session('user_device_location_lng', '');
        }
        else
        {
            $latitude = $settings->setting_site_location_lat;
            $longitude = $settings->setting_site_location_lng;
        }

        $popular_items = Item::selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( item_lat ) ) * cos( radians( item_lng ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( item_lat ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
            ->where('country_id', $site_prefer_country_id)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->orderBy('distance')
            ->orderBy('created_at', 'DESC')
            ->with('state')
            ->with('city')
            ->with('user')
            ->take(9)->get();

        $popular_items = $popular_items->shuffle();

        /**
         * get first 6 latest items
         */
        $latest_items = Item::latest('created_at')
            ->where(function ($query) use ($site_prefer_country_id) {
                $query->where('items.country_id', $site_prefer_country_id)
                    ->orWhereNull('items.country_id');
            })
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->with('state')
            ->with('city')
            ->with('user')
            ->take(6)
            ->get();

        /**
         * testimonials
         */
        $all_testimonials = Testimonial::latest('created_at')->get();

        /**
         * get latest 3 blog posts
         */
        $recent_blog = \Canvas\Post::published()->orderByDesc('published_at')->take(3)->get();

        /**
         * Start homepage header customization
         */
        $site_homepage_header_background_type = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_homepage_header_background_color = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_homepage_header_background_image = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_homepage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_homepage_header_title_font_color = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_homepage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
        /**
         * End homepage header customization
         */

        /**
         * Start initial blade view file path
         */
        $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
        $theme_view_path = $theme_view_path->getViewPath();
        /**
         * End initial blade view file path
         */

        return response()->view($theme_view_path . 'index',
            compact('categories', 'paid_items', 'popular_items', 'latest_items',
                'all_testimonials', 'recent_blog',
                'site_homepage_header_background_type', 'site_homepage_header_background_color',
                'site_homepage_header_background_image', 'site_homepage_header_background_youtube_video',
                'site_homepage_header_title_font_color', 'site_homepage_header_paragraph_font_color',
                'site_prefer_country_id'));
    }

    public function search(Request $request)
    {
        $settings = app('site_global_settings');
        $site_prefer_country_id = app('site_prefer_country_id');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.frontend.search', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        /**
         * Start filter
         */
        $search_query = empty($request->search_query) ? null : $request->search_query;
        $search_values = !empty($search_query) ? preg_split('/\s+/', $search_query, -1, PREG_SPLIT_NO_EMPTY) : array();

        // categories
        $filter_categories = empty($request->filter_categories) ? array() : $request->filter_categories;

        $category_obj = new Category();
        $item_ids = $category_obj->getItemIdsByCategoryIds($filter_categories);

        // state & city
        $filter_state = empty($request->filter_state) ? null : $request->filter_state;
        $filter_city = empty($request->filter_city) ? null : $request->filter_city;
        /**
         * End filter
         */

        /**
         * Start paid search
         */
        $paid_items_query = Item::query();

        // get paid users id array
        $subscription_obj = new Subscription();
        $paid_user_ids = $subscription_obj->getPaidUserIds();

        if(count($item_ids) > 0)
        {
            $paid_items_query->whereIn('id', $item_ids);
        }

        if(is_array($search_values) && count($search_values) > 0)
        {
            $paid_items_query->where(function ($query) use ($search_values) {
                foreach($search_values as $search_values_key => $search_value)
                {
                    $query->where('items.item_title', 'LIKE', "%".$search_value."%")
                        ->orWhere('items.item_location_str', 'LIKE', "%".$search_value."%")
                        ->orWhere('items.item_categories_string', 'LIKE', "%".$search_value."%")
                        ->orWhere('items.item_description', 'LIKE', "%".$search_value."%")
                        ->orWhere('items.item_features_string', 'LIKE', "%".$search_value."%");
                }
            });
        }

        $paid_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
            ->where(function ($query) use ($site_prefer_country_id) {
                $query->where('items.country_id', $site_prefer_country_id)
                    ->orWhereNull('items.country_id');
            })
            ->where('items.item_featured', Item::ITEM_FEATURED)
            ->where(function($query) use ($paid_user_ids) {

                $query->whereIn('items.user_id', $paid_user_ids)
                    ->orWhere('items.item_featured_by_admin', Item::ITEM_FEATURED_BY_ADMIN);
            });

        // filter paid listings state
        if(!empty($filter_state))
        {
            $paid_items_query->where('items.state_id', $filter_state);
        }

        // filter paid listings city
        if(!empty($filter_city))
        {
            $paid_items_query->where('items.city_id', $filter_city);
        }

        $paid_items_query->orderBy('items.created_at', 'DESC')
            ->distinct('items.id')
            ->with('state')
            ->with('city')
            ->with('user');

        $total_paid_items = $paid_items_query->count();
        /**
         * End paid search
         */

        /**
         * Start free search
         */
        $free_items_query = Item::query();

        // get free users id array
        //$free_user_ids = $subscription_obj->getFreeUserIds();
        $free_user_ids = $subscription_obj->getActiveUserIds();

        if(count($item_ids) > 0)
        {
            $free_items_query->whereIn('id', $item_ids);
        }

        if(is_array($search_values) && count($search_values) > 0)
        {
            $free_items_query->where(function ($query) use ($search_values) {
                foreach($search_values as $search_values_key => $search_value)
                {
                    $query->where('items.item_title', 'LIKE', "%".$search_value."%")
                        ->orWhere('items.item_location_str', 'LIKE', "%".$search_value."%")
                        ->orWhere('items.item_categories_string', 'LIKE', "%".$search_value."%")
                        ->orWhere('items.item_description', 'LIKE', "%".$search_value."%")
                        ->orWhere('items.item_features_string', 'LIKE', "%".$search_value."%");
                }
            });
        }

        $free_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
            ->where(function ($query) use ($site_prefer_country_id) {
                $query->where('items.country_id', $site_prefer_country_id)
                    ->orWhereNull('items.country_id');
            })
            ->where('items.item_featured', Item::ITEM_NOT_FEATURED)
            ->where('items.item_featured_by_admin', Item::ITEM_NOT_FEATURED_BY_ADMIN)
            ->whereIn('items.user_id', $free_user_ids);

        // filter free listings state
        if(!empty($filter_state))
        {
            $free_items_query->where('items.state_id', $filter_state);
        }

        // filter free listings city
        if(!empty($filter_city))
        {
            $free_items_query->where('items.city_id', $filter_city);
        }

        /**
         * Start filter sort by for free listing
         */
        $filter_sort_by = empty($request->filter_sort_by) ? Item::ITEMS_SORT_BY_NEWEST_CREATED : $request->filter_sort_by;
        if($filter_sort_by == Item::ITEMS_SORT_BY_NEWEST_CREATED)
        {
            $free_items_query->orderBy('items.created_at', 'DESC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_OLDEST_CREATED)
        {
            $free_items_query->orderBy('items.created_at', 'ASC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_HIGHEST_RATING)
        {
            $free_items_query->orderBy('items.item_average_rating', 'DESC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_LOWEST_RATING)
        {
            $free_items_query->orderBy('items.item_average_rating', 'ASC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_NEARBY_FIRST)
        {
            $free_items_query->selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( item_lat ) ) * cos( radians( item_lng ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( item_lat ) ) ) ) AS distance', [$this->getLatitude(), $this->getLongitude(), $this->getLatitude()])
                ->where('items.item_type', Item::ITEM_TYPE_REGULAR)
                ->orderBy('distance', 'ASC');
        }
        /**
         * End filter sort by for free listing
         */

        $free_items_query->distinct('items.id')
            ->with('state')
            ->with('city')
            ->with('user');

        $total_free_items = $free_items_query->count();
        /**
         * End free search
         */

        $querystringArray = [
            'search_query' => $search_query,
            'filter_categories' => $filter_categories,
            'filter_sort_by' => $filter_sort_by,
            'filter_state' => $filter_state,
            'filter_city' => $filter_city,
        ];

        if($total_free_items == 0 || $total_paid_items == 0)
        {
            $paid_items = $paid_items_query->paginate(10);
            $free_items = $free_items_query->paginate(10);

            if($total_free_items == 0)
            {
                $pagination = $paid_items->appends($querystringArray);
            }
            if($total_paid_items == 0)
            {
                $pagination = $free_items->appends($querystringArray);
            }
        }
        else
        {
            $num_of_pages = ceil(($total_paid_items + $total_free_items) / 10);

            $paid_items_per_page = ceil($total_paid_items / $num_of_pages) > 4 ? 4 : ceil($total_paid_items / $num_of_pages);

            $free_items_per_page = 10 - $paid_items_per_page;

            $paid_items = $paid_items_query->paginate($paid_items_per_page);
            $free_items = $free_items_query->paginate($free_items_per_page);

            if(ceil($total_paid_items / $paid_items_per_page) > ceil($total_free_items / $free_items_per_page))
            {
                $pagination = $paid_items->appends($querystringArray);
            }
            else
            {
                $pagination = $free_items->appends($querystringArray);
            }
        }

        /**
         * Start sorting the results by relevance
         */
        $props = [
            'item_title',
            'item_location_str',
            'item_categories_string',
            'item_description',
            'item_features_string',
        ];

        $paid_items = $paid_items->sortByDesc(function($paid_collection, $paid_collection_key) use ($search_values, $props) {

            // The bigger the weight, the higher the record
            $weight = 0;
            // Iterate through search terms
            foreach($search_values as $search_values_key => $search_value)
            {
                // Iterate through $props
                foreach($props as $prop)
                {
                    if(stripos($paid_collection->$prop, $search_value) !== false)
                    {
                        $weight += 1; // Increase weight if the search term is found
                    }

                }
            }
            return $weight;
        });

        $free_items = $free_items->sortByDesc(function($free_collection, $free_collection_key) use ($search_values, $props) {

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
                        $weight += 1; // Increase weight if the search term is found
                    }

                }
            }
            return $weight;
        });
        /**
         * End sorting the results by relevance
         */

        /**
         * Start fetch ads blocks
         */
        $advertisement = new Advertisement();

        $ads_before_breadcrumb = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_LISTING_SEARCH_PAGE,
            Advertisement::AD_POSITION_BEFORE_BREADCRUMB,
            Advertisement::AD_STATUS_ENABLE
        );

        $ads_after_breadcrumb = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_LISTING_SEARCH_PAGE,
            Advertisement::AD_POSITION_AFTER_BREADCRUMB,
            Advertisement::AD_STATUS_ENABLE
        );

        $ads_before_content = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_LISTING_SEARCH_PAGE,
            Advertisement::AD_POSITION_BEFORE_CONTENT,
            Advertisement::AD_STATUS_ENABLE
        );

        $ads_after_content = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_LISTING_SEARCH_PAGE,
            Advertisement::AD_POSITION_AFTER_CONTENT,
            Advertisement::AD_STATUS_ENABLE
        );
        /**
         * End fetch ads blocks
         */

        /**
         * Start initial filter
         */
        $all_printable_categories = $category_obj->getPrintableCategoriesNoDash();

        $all_states = Country::find($site_prefer_country_id)
            ->states()->orderBy('state_name')->get();

        $all_cities = collect([]);
        if(!empty($filter_state))
        {
            $state = State::find($filter_state);
            $all_cities = $state->cities()->orderBy('city_name')->get();
        }

        $total_results = $total_paid_items + $total_free_items;
        /**
         * End initial filter
         */

        /**
         * Start homepage header customization
         */
        $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
        /**
         * End homepage header customization
         */

        /**
         * Start initial blade view file path
         */
        $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
        $theme_view_path = $theme_view_path->getViewPath();
        /**
         * End initial blade view file path
         */

        return response()->view($theme_view_path . 'search',
            compact('ads_before_breadcrumb', 'ads_after_breadcrumb', 'ads_before_content', 'ads_after_content',
                    'site_innerpage_header_background_type', 'site_innerpage_header_background_color',
                    'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
                    'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color',
                    'search_query', 'filter_categories', 'filter_state', 'filter_city', 'filter_sort_by', 'paid_items',
                    'free_items', 'pagination', 'all_printable_categories', 'all_states', 'all_cities', 'total_results'));
    }


    public function about()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.frontend.about', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        if($settings->setting_page_about_enable == Setting::ABOUT_PAGE_ENABLED)
        {
            $about = $settings->setting_page_about;

            /**
             * Start inner page header customization
             */
            $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
            /**
             * End inner page header customization
             */

            /**
             * Start initial blade view file path
             */
            $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
            $theme_view_path = $theme_view_path->getViewPath();
            /**
             * End initial blade view file path
             */

            return response()->view($theme_view_path . 'about',
                compact('about', 'site_innerpage_header_background_type', 'site_innerpage_header_background_color',
                        'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
                        'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color'));
        }
        else
        {
            return redirect()->route('page.home');
        }
    }

    public function contact()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.frontend.contact', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_faq = Faq::orderBy('faqs_order')->get();

        /**
         * Start inner page header customization
         */
        $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
        /**
         * End inner page header customization
         */

        /**
         * Start initial blade view file path
         */
        $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
        $theme_view_path = $theme_view_path->getViewPath();
        /**
         * End initial blade view file path
         */

        /**
         * Start initial Google reCAPTCHA version 2
         */
        if($settings->setting_site_recaptcha_contact_enable == Setting::SITE_RECAPTCHA_CONTACT_ENABLE)
        {
            config_re_captcha($settings->setting_site_recaptcha_site_key, $settings->setting_site_recaptcha_secret_key);
        }
        /**
         * End initial Google reCAPTCHA version 2
         */

        return response()->view($theme_view_path . 'contact',
            compact('all_faq', 'site_innerpage_header_background_type',
                    'site_innerpage_header_background_color', 'site_innerpage_header_background_image',
                    'site_innerpage_header_background_youtube_video', 'site_innerpage_header_title_font_color',
                    'site_innerpage_header_paragraph_font_color'));
    }

    public function doContact(Request $request)
    {
        $settings = app('site_global_settings');

        $validation_array = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|max:255',
            'message' => 'required',
        ];

        // Start Google reCAPTCHA version 2
        if($settings->setting_site_recaptcha_contact_enable == Setting::SITE_RECAPTCHA_CONTACT_ENABLE)
        {
            config_re_captcha($settings->setting_site_recaptcha_site_key, $settings->setting_site_recaptcha_secret_key);

            $validation_array = [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'subject' => 'required|max:255',
                'message' => 'required',
                'g-recaptcha-response' => 'recaptcha',
            ];
        }
        // End Google reCAPTCHA version 2

        $request->validate($validation_array);

        /**
         * Start initial SMTP settings
         */
        if($settings->settings_site_smtp_enabled == Setting::SITE_SMTP_ENABLED)
        {
            // config SMTP
            config_smtp(
                $settings->settings_site_smtp_sender_name,
                $settings->settings_site_smtp_sender_email,
                $settings->settings_site_smtp_host,
                $settings->settings_site_smtp_port,
                $settings->settings_site_smtp_encryption,
                $settings->settings_site_smtp_username,
                $settings->settings_site_smtp_password
            );
        }
        /**
         * End initial SMTP settings
         */

        if(!empty($settings->setting_site_name))
        {
            // set up APP_NAME
            config([
                'app.name' => $settings->setting_site_name,
            ]);
        }

        // send an email notification to admin
        $email_admin = User::getAdmin();
        $email_subject = __('email.contact.subject');
        $email_notify_message = [
            __('email.contact.body.body-1', ['first_name' => $request->first_name, 'last_name' => $request->last_name]),
            __('email.contact.body.body-2', ['subject' => $request->subject]),
            __('email.contact.body.body-3', ['first_name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email]),
            __('email.contact.body.body-4'),
            $request->message,
        ];

        try
        {
            // to admin
            Mail::to($email_admin)->send(
                new Notification(
                    $email_subject,
                    $email_admin->name,
                    null,
                    $email_notify_message
                )
            );

            \Session::flash('flash_message', __('alert.message-send'));
            \Session::flash('flash_type', 'success');

        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage() . "\n" . $e->getTraceAsString());

            \Session::flash('flash_message', __('theme_directory_hub.email.alert.sending-problem'));
            \Session::flash('flash_type', 'danger');
        }

        return redirect()->route('page.contact');
    }

    public function categories(Request $request)
    {
        $settings = app('site_global_settings');
        $site_prefer_country_id = app('site_prefer_country_id');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.frontend.categories', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */
        $subscription_obj = new Subscription();

        $active_user_ids = $subscription_obj->getActiveUserIds();
        $categories = Category::withCount(['allItems' => function ($query) use ($active_user_ids, $site_prefer_country_id) {
            $query->whereIn('items.user_id', $active_user_ids)
                ->where('items.item_status', Item::ITEM_PUBLISHED)
                ->where(function ($query) use ($site_prefer_country_id) {
                    $query->where('items.country_id', $site_prefer_country_id)
                        ->orWhereNull('items.country_id');
                });
        }])
            ->where('category_parent_id', null)
            ->orderBy('all_items_count', 'desc')->get();

        /**
         * Do listing query
         * 1. get paid listings and free listings.
         * 2. decide how many paid and free listings per page and total pages.
         * 3. decide the pagination to paid or free listings
         * 4. run query and render
         */

        // paid listing
        $paid_items_query = Item::query();

        /**
         * Start filter for paid listing
         */
        // categories
        $filter_categories = empty($request->filter_categories) ? array() : $request->filter_categories;

        $category_obj = new Category();
        $item_ids = $category_obj->getItemIdsByCategoryIds($filter_categories);

        // state & city
        $filter_state = empty($request->filter_state) ? null : $request->filter_state;
        $filter_city = empty($request->filter_city) ? null : $request->filter_city;
        /**
         * End filter for paid listing
         */

        // get paid users id array
        $paid_user_ids = $subscription_obj->getPaidUserIds();

        if(count($item_ids) > 0)
        {
            $paid_items_query->whereIn('id', $item_ids);
        }

        $paid_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
            ->where(function ($query) use ($site_prefer_country_id) {
                $query->where('items.country_id', $site_prefer_country_id)
                    ->orWhereNull('items.country_id');
            })
            ->where('items.item_featured', Item::ITEM_FEATURED)
            ->where(function($query) use ($paid_user_ids) {

                $query->whereIn('items.user_id', $paid_user_ids)
                    ->orWhere('items.item_featured_by_admin', Item::ITEM_FEATURED_BY_ADMIN);
            });

        // filter paid listings state
        if(!empty($filter_state))
        {
            $paid_items_query->where('items.state_id', $filter_state);
        }

        // filter paid listings city
        if(!empty($filter_city))
        {
            $paid_items_query->where('items.city_id', $filter_city);
        }

        $paid_items_query->orderBy('items.created_at', 'DESC')
            ->distinct('items.id')
            ->with('state')
            ->with('city')
            ->with('user');

        $total_paid_items = $paid_items_query->count();

        // free listing
        $free_items_query = Item::query();

        // get free users id array
        //$free_user_ids = $subscription_obj->getFreeUserIds();
        //$free_user_ids = $subscription_obj->getActiveUserIds();
        $free_user_ids = $active_user_ids;

        if(count($item_ids) > 0)
        {
            $free_items_query->whereIn('id', $item_ids);
        }

        $free_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
            ->where(function ($query) use ($site_prefer_country_id) {
                $query->where('items.country_id', $site_prefer_country_id)
                    ->orWhereNull('items.country_id');
            })
            ->where('items.item_featured', Item::ITEM_NOT_FEATURED)
            ->where('items.item_featured_by_admin', Item::ITEM_NOT_FEATURED_BY_ADMIN)
            ->whereIn('items.user_id', $free_user_ids);

        // filter free listings state
        if(!empty($filter_state))
        {
            $free_items_query->where('items.state_id', $filter_state);
        }

        // filter free listings city
        if(!empty($filter_city))
        {
            $free_items_query->where('items.city_id', $filter_city);
        }

        /**
         * Start filter sort by for free listing
         */
        $filter_sort_by = empty($request->filter_sort_by) ? Item::ITEMS_SORT_BY_NEWEST_CREATED : $request->filter_sort_by;
        if($filter_sort_by == Item::ITEMS_SORT_BY_NEWEST_CREATED)
        {
            $free_items_query->orderBy('items.created_at', 'DESC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_OLDEST_CREATED)
        {
            $free_items_query->orderBy('items.created_at', 'ASC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_HIGHEST_RATING)
        {
            $free_items_query->orderBy('items.item_average_rating', 'DESC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_LOWEST_RATING)
        {
            $free_items_query->orderBy('items.item_average_rating', 'ASC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_NEARBY_FIRST)
        {
            $free_items_query->selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( item_lat ) ) * cos( radians( item_lng ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( item_lat ) ) ) ) AS distance', [$this->getLatitude(), $this->getLongitude(), $this->getLatitude()])
                ->where('items.item_type', Item::ITEM_TYPE_REGULAR)
                ->orderBy('distance', 'ASC');
        }
        /**
         * End filter sort by for free listing
         */

        $free_items_query->distinct('items.id')
            ->with('state')
            ->with('city')
            ->with('user');

        $total_free_items = $free_items_query->count();

        $querystringArray = [
            'filter_categories' => $filter_categories,
            'filter_sort_by' => $filter_sort_by,
            'filter_state' => $filter_state,
            'filter_city' => $filter_city,
        ];

        if($total_free_items == 0 || $total_paid_items == 0)
        {
            $paid_items = $paid_items_query->paginate(10);
            $free_items = $free_items_query->paginate(10);

            if($total_free_items == 0)
            {
                $pagination = $paid_items->appends($querystringArray);
            }
            if($total_paid_items == 0)
            {
                $pagination = $free_items->appends($querystringArray);
            }
        }
        else
        {
            $num_of_pages = ceil(($total_paid_items + $total_free_items) / 10);

            $paid_items_per_page = ceil($total_paid_items / $num_of_pages) > 4 ? 4 : ceil($total_paid_items / $num_of_pages);

            $free_items_per_page = 10 - $paid_items_per_page;

            $paid_items = $paid_items_query->paginate($paid_items_per_page);
            $free_items = $free_items_query->paginate($free_items_per_page);

            if(ceil($total_paid_items / $paid_items_per_page) > ceil($total_free_items / $free_items_per_page))
            {
                $pagination = $paid_items->appends($querystringArray);
            }
            else
            {
                $pagination = $free_items->appends($querystringArray);
            }
        }
        /**
         * End do listing query
         */

        /**
         * Start fetch ads blocks
         */
        $advertisement = new Advertisement();

        $ads_before_breadcrumb = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
            Advertisement::AD_POSITION_BEFORE_BREADCRUMB,
            Advertisement::AD_STATUS_ENABLE
            );

        $ads_after_breadcrumb = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
            Advertisement::AD_POSITION_AFTER_BREADCRUMB,
            Advertisement::AD_STATUS_ENABLE
        );

        $ads_before_content = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
            Advertisement::AD_POSITION_BEFORE_CONTENT,
            Advertisement::AD_STATUS_ENABLE
        );

        $ads_after_content = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
            Advertisement::AD_POSITION_AFTER_CONTENT,
            Advertisement::AD_STATUS_ENABLE
        );

        $ads_before_sidebar_content = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
            Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT,
            Advertisement::AD_STATUS_ENABLE
        );

        $ads_after_sidebar_content = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
            Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT,
            Advertisement::AD_STATUS_ENABLE
        );
        /**
         * End fetch ads blocks
         */

        /**
         * Start inner page header customization
         */
        $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
        /**
         * End inner page header customization
         */

        /**
         * Start initial filter
         */
        $all_printable_categories = $category_obj->getPrintableCategoriesNoDash();

        $all_states = Country::find($site_prefer_country_id)
            ->states()
            ->orderBy('state_name')
            ->get();

        $all_cities = collect([]);
        if(!empty($filter_state))
        {
            $state = State::find($filter_state);
            $all_cities = $state->cities()->orderBy('city_name')->get();
        }

        $total_results = $total_paid_items + $total_free_items;
        /**
         * End initial filter
         */

        /**
         * Start initial blade view file path
         */
        $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
        $theme_view_path = $theme_view_path->getViewPath();
        /**
         * End initial blade view file path
         */

        return response()->view($theme_view_path . 'categories',
            compact('categories', 'paid_items', 'free_items', 'pagination', 'all_states',
                'ads_before_breadcrumb', 'ads_after_breadcrumb', 'ads_before_content', 'ads_after_content',
                'ads_before_sidebar_content', 'ads_after_sidebar_content', 'site_innerpage_header_background_type',
                'site_innerpage_header_background_color', 'site_innerpage_header_background_image',
                'site_innerpage_header_background_youtube_video', 'site_innerpage_header_title_font_color',
                'site_innerpage_header_paragraph_font_color', 'filter_sort_by', 'all_printable_categories',
                'filter_categories', 'site_prefer_country_id', 'filter_state', 'filter_city', 'all_cities',
                'total_results'));
    }

    public function category(Request $request, string $category_slug)
    {
        $category = Category::where('category_slug', $category_slug)->first();

        if($category)
        {
            $settings = app('site_global_settings');
            $site_prefer_country_id = app('site_prefer_country_id');

            /**
             * Start SEO
             */
            SEOMeta::setTitle($category->category_name . ' - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
            SEOMeta::setDescription($category->category_description);
            SEOMeta::setCanonical(URL::current());
            SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
            /**
             * End SEO
             */

            /**
             * Get parent and children categories
             */
            $parent_categories = $category->allParents();

            // get one level down sub-categories
            $children_categories = $category->children()->orderBy('category_name')->get();

            // need to give a root category for each item in a category listing page
            $parent_category_id = $category->id;

            /**
             * Do listing query
             * 1. get paid listings and free listings.
             * 2. decide how many paid and free listings per page and total pages.
             * 3. decide the pagination to paid or free listings
             * 4. run query and render
             */

            // paid listing
            $paid_items_query = Item::query();

            /**
             * Start filter for paid listing
             */
            // categories
            $filter_categories = empty($request->filter_categories) ? array() : $request->filter_categories;
            $category_obj = new Category();

            if(count($filter_categories) > 0)
            {
                $item_ids = $category_obj->getItemIdsByCategoryIds($filter_categories);
            }
            else
            {
                // Get all child categories of this category
                $all_child_categories = collect();
                $all_child_categories_ids = array();
                $category->allChildren($category, $all_child_categories);
                foreach($all_child_categories as $key => $all_child_category)
                {
                    $all_child_categories_ids[] = $all_child_category->id;
                }

                $item_ids = $category_obj->getItemIdsByCategoryIds($all_child_categories_ids);
            }

            // state & city
            $filter_state = empty($request->filter_state) ? null : $request->filter_state;
            $filter_city = empty($request->filter_city) ? null : $request->filter_city;
            /**
             * End filter for paid listing
             */

            // get paid users id array
            $subscription_obj = new Subscription();
            $paid_user_ids = $subscription_obj->getPaidUserIds();

            $paid_items_query->whereIn('id', $item_ids);

            $paid_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
                ->where(function ($query) use ($site_prefer_country_id) {
                    $query->where('items.country_id', $site_prefer_country_id)
                        ->orWhereNull('items.country_id');
                })
                ->where('items.item_featured', Item::ITEM_FEATURED)
                ->where(function($query) use ($paid_user_ids) {

                    $query->whereIn('items.user_id', $paid_user_ids)
                        ->orWhere('items.item_featured_by_admin', Item::ITEM_FEATURED_BY_ADMIN);
                });

            // filter paid listings state
            if(!empty($filter_state))
            {
                $paid_items_query->where('items.state_id', $filter_state);
            }

            // filter paid listings city
            if(!empty($filter_city))
            {
                $paid_items_query->where('items.city_id', $filter_city);
            }

            $paid_items_query->orderBy('items.created_at', 'DESC')
                ->distinct('items.id')
                ->with('state')
                ->with('city')
                ->with('user');

            $total_paid_items = $paid_items_query->count();

            // free listing
            $free_items_query = Item::query();

            // get free users id array
            //$free_user_ids = $subscription_obj->getFreeUserIds();
            $free_user_ids = $subscription_obj->getActiveUserIds();

            $free_items_query->whereIn('id', $item_ids);

            $free_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
                ->where(function ($query) use ($site_prefer_country_id) {
                    $query->where('items.country_id', $site_prefer_country_id)
                        ->orWhereNull('items.country_id');
                })
                ->where('items.item_featured', Item::ITEM_NOT_FEATURED)
                ->where('items.item_featured_by_admin', Item::ITEM_NOT_FEATURED_BY_ADMIN)
                ->whereIn('items.user_id', $free_user_ids);

            // filter free listings state
            if(!empty($filter_state))
            {
                $free_items_query->where('items.state_id', $filter_state);
            }

            // filter free listings city
            if(!empty($filter_city))
            {
                $free_items_query->where('items.city_id', $filter_city);
            }

            /**
             * Start filter sort by for free listing
             */
            $filter_sort_by = empty($request->filter_sort_by) ? Item::ITEMS_SORT_BY_NEWEST_CREATED : $request->filter_sort_by;
            if($filter_sort_by == Item::ITEMS_SORT_BY_NEWEST_CREATED)
            {
                $free_items_query->orderBy('items.created_at', 'DESC');
            }
            elseif($filter_sort_by == Item::ITEMS_SORT_BY_OLDEST_CREATED)
            {
                $free_items_query->orderBy('items.created_at', 'ASC');
            }
            elseif($filter_sort_by == Item::ITEMS_SORT_BY_HIGHEST_RATING)
            {
                $free_items_query->orderBy('items.item_average_rating', 'DESC');
            }
            elseif($filter_sort_by == Item::ITEMS_SORT_BY_LOWEST_RATING)
            {
                $free_items_query->orderBy('items.item_average_rating', 'ASC');
            }
            elseif($filter_sort_by == Item::ITEMS_SORT_BY_NEARBY_FIRST)
            {
                $free_items_query->selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( item_lat ) ) * cos( radians( item_lng ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( item_lat ) ) ) ) AS distance', [$this->getLatitude(), $this->getLongitude(), $this->getLatitude()])
                    ->where('items.item_type', Item::ITEM_TYPE_REGULAR)
                    ->orderBy('distance', 'ASC');
            }
            /**
             * End filter sort by for free listing
             */

            $free_items_query->distinct('items.id')
                ->with('state')
                ->with('city')
                ->with('user');

            $total_free_items = $free_items_query->count();

            $querystringArray = [
                'filter_categories' => $filter_categories,
                'filter_sort_by' => $filter_sort_by,
                'filter_state' => $filter_state,
                'filter_city' => $filter_city,
            ];

            if($total_free_items == 0 || $total_paid_items == 0)
            {
                $paid_items = $paid_items_query->paginate(10);
                $free_items = $free_items_query->paginate(10);

                if($total_free_items == 0)
                {
                    $pagination = $paid_items->appends($querystringArray);
                }
                if($total_paid_items == 0)
                {
                    $pagination = $free_items->appends($querystringArray);
                }
            }
            else
            {
                $num_of_pages = ceil(($total_paid_items + $total_free_items) / 10);
                $paid_items_per_page = ceil($total_paid_items / $num_of_pages) < 4 ? 4 : ceil($total_paid_items / $num_of_pages);
                $free_items_per_page = 10 - $paid_items_per_page;

                $paid_items = $paid_items_query->paginate($paid_items_per_page);
                $free_items = $free_items_query->paginate($free_items_per_page);

                if(ceil($total_paid_items / $paid_items_per_page) > ceil($total_free_items / $free_items_per_page))
                {
                    $pagination = $paid_items->appends($querystringArray);
                }
                else
                {
                    $pagination = $free_items->appends($querystringArray);
                }
            }
            /**
             * End do listing query
             */

            /**
             * Start fetch ads blocks
             */
            $advertisement = new Advertisement();

            $ads_before_breadcrumb = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_BEFORE_BREADCRUMB,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_breadcrumb = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_AFTER_BREADCRUMB,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_BEFORE_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_AFTER_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_sidebar_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_sidebar_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );
            /**
             * End fetch ads blocks
             */


            /**
             * Start initial filter
             */
            $all_states = Country::find($site_prefer_country_id)
                ->states()
                ->orderBy('state_name')->get();

            $all_cities = collect([]);
            if(!empty($filter_state))
            {
                $state = State::find($filter_state);
                $all_cities = $state->cities()->orderBy('city_name')->get();
            }

            $total_results = $total_paid_items + $total_free_items;
            /**
             * End initial filter
             */


            /**
             * Start inner page header customization
             */
            if($category->category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_INHERITED)
            {
                $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
                    ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

                $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
                    ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

                $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
                    ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
                $site_innerpage_header_background_image = Storage::disk('public')->url('customization/' . $site_innerpage_header_background_image);

                $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
                    ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
            }
            else
            {
                if($category->category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_COLOR)
                {
                    $site_innerpage_header_background_type = Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR;
                }
                elseif($category->category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_IMAGE)
                {
                    $site_innerpage_header_background_type = Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE;
                }
                elseif($category->category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_VIDEO)
                {
                    $site_innerpage_header_background_type = Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO;
                }

                $site_innerpage_header_background_color = $category->category_header_background_color;

                $site_innerpage_header_background_image = $category->category_header_background_image;
                $site_innerpage_header_background_image = Storage::disk('public')->url('category/' . $site_innerpage_header_background_image);

                $site_innerpage_header_background_youtube_video = $category->category_header_background_youtube_video;
            }

            $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
            /**
             * End inner page header customization
             */

            /**
             * Start initial blade view file path
             */
            $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
            $theme_view_path = $theme_view_path->getViewPath();
            /**
             * End initial blade view file path
             */

            return response()->view($theme_view_path . 'category',
                compact('category', 'paid_items', 'free_items', 'pagination', 'all_states',
                    'ads_before_breadcrumb', 'ads_after_breadcrumb', 'ads_before_content', 'ads_after_content',
                    'ads_before_sidebar_content', 'ads_after_sidebar_content', 'parent_categories', 'children_categories',
                    'parent_category_id', 'site_innerpage_header_background_type', 'site_innerpage_header_background_color',
                    'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
                    'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color', 'filter_sort_by',
                    'filter_categories', 'filter_state', 'filter_city', 'all_cities', 'total_results'));
        }
        else
        {
            abort(404);
        }
    }

    public function categoryByState(Request $request, string $category_slug, string $state_slug)
    {
        $category = Category::where('category_slug', $category_slug)->first();
        $state = State::where('state_slug', $state_slug)->first();

        if($category && $state)
        {
            $settings = app('site_global_settings');
            $site_prefer_country_id = app('site_prefer_country_id');

            /**
             * Start SEO
             */
            SEOMeta::setTitle($category->category_name . ' of ' . $state->state_name . ' - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
            SEOMeta::setDescription('');
            SEOMeta::setCanonical(URL::current());
            SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
            /**
             * End SEO
             */

            /**
             * Get parent and children categories
             */
            $parent_categories = $category->allParents();

            // get one level down sub-categories
            $children_categories = $category->children()->orderBy('category_name')->get();

            // need to give a root category for each item in a category listing page
            $parent_category_id = $category->id;

            // Get all child categories of this category
            $all_child_categories = collect();
            $all_child_categories_ids = array();
            $category->allChildren($category, $all_child_categories);
            foreach($all_child_categories as $key => $all_child_category)
            {
                $all_child_categories_ids[] = $all_child_category->id;
            }

            /**
             * Do listing query
             * 1. get paid listings and free listings.
             * 2. decide how many paid and free listings per page and total pages.
             * 3. decide the pagination to paid or free listings
             * 4. run query and render
             */

            // paid listing
            $paid_items_query = Item::query();

            /**
             * Start filter for paid listing
             */
            // categories
            $filter_categories = empty($request->filter_categories) ? array() : $request->filter_categories;
            $category_obj = new Category();

            $all_item_ids = $category_obj->getItemIdsByCategoryIds($all_child_categories_ids);

            if(count($filter_categories) > 0)
            {
                $item_ids = $category_obj->getItemIdsByCategoryIds($filter_categories);
            }
            else
            {
                $item_ids = $all_item_ids;
            }

            // city
            $filter_city = empty($request->filter_city) ? null : $request->filter_city;
            /**
             * End filter for paid listing
             */

            // get paid users id array
            $subscription_obj = new Subscription();
            $paid_user_ids = $subscription_obj->getPaidUserIds();

            if(count($item_ids) > 0)
            {
                $paid_items_query->whereIn('id', $item_ids);
            }

            $paid_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
                ->where('items.country_id', $site_prefer_country_id)
                ->where('items.state_id', $state->id)
                ->where('items.item_featured', Item::ITEM_FEATURED)
                ->where(function($query) use ($paid_user_ids) {

                    $query->whereIn('items.user_id', $paid_user_ids)
                        ->orWhere('items.item_featured_by_admin', Item::ITEM_FEATURED_BY_ADMIN);
                });

            // filter paid listings city
            if(!empty($filter_city))
            {
                $paid_items_query->where('items.city_id', $filter_city);
            }

            $paid_items_query->orderBy('items.created_at', 'DESC')
                ->distinct('items.id')
                ->with('state')
                ->with('city')
                ->with('user');

            $total_paid_items = $paid_items_query->count();

            // free listing
            $free_items_query = Item::query();

            // get free users id array
            //$free_user_ids = $subscription_obj->getFreeUserIds();
            $free_user_ids = $subscription_obj->getActiveUserIds();

            if(count($item_ids) > 0)
            {
                $free_items_query->whereIn('id', $item_ids);
            }

            $free_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
                ->where('items.country_id', $site_prefer_country_id)
                ->where('items.state_id', $state->id)
                ->where('items.item_featured', Item::ITEM_NOT_FEATURED)
                ->where('items.item_featured_by_admin', Item::ITEM_NOT_FEATURED_BY_ADMIN)
                ->whereIn('items.user_id', $free_user_ids);

            // filter free listings city
            if(!empty($filter_city))
            {
                $free_items_query->where('items.city_id', $filter_city);
            }

            /**
             * Start filter sort by for free listing
             */
            $filter_sort_by = empty($request->filter_sort_by) ? Item::ITEMS_SORT_BY_NEWEST_CREATED : $request->filter_sort_by;
            if($filter_sort_by == Item::ITEMS_SORT_BY_NEWEST_CREATED)
            {
                $free_items_query->orderBy('items.created_at', 'DESC');
            }
            elseif($filter_sort_by == Item::ITEMS_SORT_BY_OLDEST_CREATED)
            {
                $free_items_query->orderBy('items.created_at', 'ASC');
            }
            elseif($filter_sort_by == Item::ITEMS_SORT_BY_HIGHEST_RATING)
            {
                $free_items_query->orderBy('items.item_average_rating', 'DESC');
            }
            elseif($filter_sort_by == Item::ITEMS_SORT_BY_LOWEST_RATING)
            {
                $free_items_query->orderBy('items.item_average_rating', 'ASC');
            }
            elseif($filter_sort_by == Item::ITEMS_SORT_BY_NEARBY_FIRST)
            {
                $free_items_query->selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( item_lat ) ) * cos( radians( item_lng ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( item_lat ) ) ) ) AS distance', [$this->getLatitude(), $this->getLongitude(), $this->getLatitude()])
                    ->orderBy('distance', 'ASC');
            }
            /**
             * End filter sort by for free listing
             */

            $free_items_query->distinct('items.id')
                ->with('state')
                ->with('city')
                ->with('user');

            $total_free_items = $free_items_query->count();

            $querystringArray = [
                'filter_categories' => $filter_categories,
                'filter_sort_by' => $filter_sort_by,
                'filter_city' => $filter_city,
            ];

            if($total_free_items == 0 || $total_paid_items == 0)
            {
                $paid_items = $paid_items_query->paginate(10);
                $free_items = $free_items_query->paginate(10);

                if($total_free_items == 0)
                {
                    $pagination = $paid_items->appends($querystringArray);
                }
                if($total_paid_items == 0)
                {
                    $pagination = $free_items->appends($querystringArray);
                }
            }
            else
            {
                $num_of_pages = ceil(($total_paid_items + $total_free_items) / 10);
                $paid_items_per_page = ceil($total_paid_items / $num_of_pages) < 4 ? 4 : ceil($total_paid_items / $num_of_pages);
                $free_items_per_page = 10 - $paid_items_per_page;

                $paid_items = $paid_items_query->paginate($paid_items_per_page);
                $free_items = $free_items_query->paginate($free_items_per_page);

                if(ceil($total_paid_items / $paid_items_per_page) > ceil($total_free_items / $free_items_per_page))
                {
                    $pagination = $paid_items->appends($querystringArray);
                }
                else
                {
                    $pagination = $free_items->appends($querystringArray);
                }
            }
            /**
             * End do listing query
             */

            /**
             * Start fetch ads blocks
             */
            $advertisement = new Advertisement();

            $ads_before_breadcrumb = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_BEFORE_BREADCRUMB,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_breadcrumb = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_AFTER_BREADCRUMB,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_BEFORE_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_AFTER_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_sidebar_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_sidebar_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );
            /**
             * End fetch ads blocks
             */

            $active_user_ids = $subscription_obj->getActiveUserIds();

            $item_select_city_query = Item::query();
            $item_select_city_query->select('items.city_id')
                ->whereIn('id', $all_item_ids)
                ->where("items.item_status", Item::ITEM_PUBLISHED)
                ->where('items.country_id', $site_prefer_country_id)
                ->where("items.state_id", $state->id)
                ->whereIn('items.user_id', $active_user_ids)
                ->groupBy('items.city_id')
                ->with('city');

            $all_item_cities = $item_select_city_query->get();

            /**
             * Start initial filter
             */
            $filter_all_cities = $state->cities()->orderBy('city_name')->get();
            $total_results = $total_paid_items + $total_free_items;
            /**
             * End initial filter
             */

            /**
             * Start inner page header customization
             */
            if($category->category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_INHERITED)
            {
                $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
                    ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

                $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
                    ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

                $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
                    ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
                $site_innerpage_header_background_image = Storage::disk('public')->url('customization/' . $site_innerpage_header_background_image);

                $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
                    ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
            }
            else
            {
                if($category->category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_COLOR)
                {
                    $site_innerpage_header_background_type = Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR;
                }
                elseif($category->category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_IMAGE)
                {
                    $site_innerpage_header_background_type = Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE;
                }
                elseif($category->category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_VIDEO)
                {
                    $site_innerpage_header_background_type = Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO;
                }

                $site_innerpage_header_background_color = $category->category_header_background_color;

                $site_innerpage_header_background_image = $category->category_header_background_image;
                $site_innerpage_header_background_image = Storage::disk('public')->url('category/' . $site_innerpage_header_background_image);

                $site_innerpage_header_background_youtube_video = $category->category_header_background_youtube_video;
            }

            $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
            /**
             * End inner page header customization
             */

            /**
             * Start initial blade view file path
             */
            $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
            $theme_view_path = $theme_view_path->getViewPath();
            /**
             * End initial blade view file path
             */

            return response()->view($theme_view_path . 'category.state',
                compact('category', 'state', 'paid_items', 'free_items', 'pagination',
                    'all_item_cities', 'ads_before_breadcrumb', 'ads_after_breadcrumb', 'ads_before_content', 'ads_after_content',
                    'ads_before_sidebar_content', 'ads_after_sidebar_content', 'parent_categories', 'children_categories',
                    'parent_category_id', 'site_innerpage_header_background_type', 'site_innerpage_header_background_color',
                    'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
                    'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color',
                    'filter_sort_by', 'filter_categories', 'filter_city', 'filter_all_cities', 'total_results'));
        }
        else
        {
            abort(404);
        }
    }

    public function categoryByStateCity(Request $request, string $category_slug, string $state_slug, string $city_slug)
    {
        $category = Category::where('category_slug', $category_slug)->first();

        if($category)
        {
            $state = State::where('state_slug', $state_slug)->first();

            if($state)
            {
                $city = $state->cities()->where('city_slug', $city_slug)->first();

                if($city)
                {
                    $settings = app('site_global_settings');
                    $site_prefer_country_id = app('site_prefer_country_id');

                    /**
                     * Start SEO
                     */
                    SEOMeta::setTitle($category->category_name . ' of ' . $state->state_name . ', ' . $city->city_name . ' - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
                    SEOMeta::setDescription('');
                    SEOMeta::setCanonical(URL::current());
                    SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
                    /**
                     * End SEO
                     */

                    /**
                     * Get parent and children categories
                     */
                    $parent_categories = $category->allParents();

                    // get one level down sub-categories
                    $children_categories = $category->children()->orderBy('category_name')->get();

                    // Get all child categories of this category
                    $all_child_categories = collect();
                    $all_child_categories_ids = array();
                    $category->allChildren($category, $all_child_categories);
                    foreach($all_child_categories as $key => $all_child_category)
                    {
                        $all_child_categories_ids[] = $all_child_category->id;
                    }

                    // need to give a root category for each item in a category listing page
                    $parent_category_id = $category->id;

                    /**
                     * Do listing query
                     * 1. get paid listings and free listings.
                     * 2. decide how many paid and free listings per page and total pages.
                     * 3. decide the pagination to paid or free listings
                     * 4. run query and render
                     */

                    // paid listing
                    $paid_items_query = Item::query();

                    /**
                     * Start filter for paid listing
                     */
                    // categories
                    $filter_categories = empty($request->filter_categories) ? array() : $request->filter_categories;
                    $category_obj = new Category();

                    $all_item_ids = $category_obj->getItemIdsByCategoryIds($all_child_categories_ids);

                    if(count($filter_categories) > 0)
                    {
                        $item_ids = $category_obj->getItemIdsByCategoryIds($filter_categories);
                    }
                    else
                    {
                        $item_ids = $all_item_ids;
                    }
                    /**
                     * End filter for paid listing
                     */

                    // get paid users id array
                    $subscription_obj = new Subscription();
                    $paid_user_ids = $subscription_obj->getPaidUserIds();

                    if(count($item_ids) > 0)
                    {
                        $paid_items_query->whereIn('id', $item_ids);
                    }

                    $paid_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
                        ->where('items.country_id', $site_prefer_country_id)
                        ->where('items.state_id', $state->id)
                        ->where('items.city_id', $city->id)
                        ->where('items.item_featured', Item::ITEM_FEATURED)
                        ->where(function($query) use ($paid_user_ids) {

                            $query->whereIn('items.user_id', $paid_user_ids)
                                ->orWhere('items.item_featured_by_admin', Item::ITEM_FEATURED_BY_ADMIN);
                        });

                    $paid_items_query->orderBy('items.created_at', 'DESC')
                        ->distinct('items.id')
                        ->with('state')
                        ->with('city')
                        ->with('user');

                    $total_paid_items = $paid_items_query->count();

                    // free listing
                    $free_items_query = Item::query();

                    // get free users id array
                    //$free_user_ids = $subscription_obj->getFreeUserIds();
                    $free_user_ids = $subscription_obj->getActiveUserIds();

                    if(count($item_ids) > 0)
                    {
                        $free_items_query->whereIn('id', $item_ids);
                    }

                    $free_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
                        ->where('items.country_id', $site_prefer_country_id)
                        ->where('items.state_id', $state->id)
                        ->where('items.city_id', $city->id)
                        ->where('items.item_featured', Item::ITEM_NOT_FEATURED)
                        ->where('items.item_featured_by_admin', Item::ITEM_NOT_FEATURED_BY_ADMIN)
                        ->whereIn('items.user_id', $free_user_ids);

                    /**
                     * Start filter sort by for free listing
                     */
                    $filter_sort_by = empty($request->filter_sort_by) ? Item::ITEMS_SORT_BY_NEWEST_CREATED : $request->filter_sort_by;
                    if($filter_sort_by == Item::ITEMS_SORT_BY_NEWEST_CREATED)
                    {
                        $free_items_query->orderBy('items.created_at', 'DESC');
                    }
                    elseif($filter_sort_by == Item::ITEMS_SORT_BY_OLDEST_CREATED)
                    {
                        $free_items_query->orderBy('items.created_at', 'ASC');
                    }
                    elseif($filter_sort_by == Item::ITEMS_SORT_BY_HIGHEST_RATING)
                    {
                        $free_items_query->orderBy('items.item_average_rating', 'DESC');
                    }
                    elseif($filter_sort_by == Item::ITEMS_SORT_BY_LOWEST_RATING)
                    {
                        $free_items_query->orderBy('items.item_average_rating', 'ASC');
                    }
                    elseif($filter_sort_by == Item::ITEMS_SORT_BY_NEARBY_FIRST)
                    {
                        $free_items_query->selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( item_lat ) ) * cos( radians( item_lng ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( item_lat ) ) ) ) AS distance', [$this->getLatitude(), $this->getLongitude(), $this->getLatitude()])
                            ->orderBy('distance', 'ASC');
                    }
                    /**
                     * End filter sort by for free listing
                     */

                    $free_items_query->distinct('items.id')
                        ->with('state')
                        ->with('city')
                        ->with('user');

                    $total_free_items = $free_items_query->count();

                    $querystringArray = [
                        'filter_categories' => $filter_categories,
                        'filter_sort_by' => $filter_sort_by,
                    ];

                    if($total_free_items == 0 || $total_paid_items == 0)
                    {
                        $paid_items = $paid_items_query->paginate(10);
                        $free_items = $free_items_query->paginate(10);

                        if($total_free_items == 0)
                        {
                            $pagination = $paid_items->appends($querystringArray);
                        }
                        if($total_paid_items == 0)
                        {
                            $pagination = $free_items->appends($querystringArray);
                        }
                    }
                    else
                    {
                        $num_of_pages = ceil(($total_paid_items + $total_free_items) / 10);
                        $paid_items_per_page = ceil($total_paid_items / $num_of_pages) < 4 ? 4 : ceil($total_paid_items / $num_of_pages);
                        $free_items_per_page = 10 - $paid_items_per_page;

                        $paid_items = $paid_items_query->paginate($paid_items_per_page);
                        $free_items = $free_items_query->paginate($free_items_per_page);

                        if(ceil($total_paid_items / $paid_items_per_page) > ceil($total_free_items / $free_items_per_page))
                        {
                            $pagination = $paid_items->appends($querystringArray);
                        }
                        else
                        {
                            $pagination = $free_items->appends($querystringArray);
                        }
                    }
                    /**
                     * End do listing query
                     */

                    /**
                     * Start fetch ads blocks
                     */
                    $advertisement = new Advertisement();

                    $ads_before_breadcrumb = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                        Advertisement::AD_POSITION_BEFORE_BREADCRUMB,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_after_breadcrumb = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                        Advertisement::AD_POSITION_AFTER_BREADCRUMB,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_before_content = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                        Advertisement::AD_POSITION_BEFORE_CONTENT,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_after_content = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                        Advertisement::AD_POSITION_AFTER_CONTENT,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_before_sidebar_content = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                        Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_after_sidebar_content = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                        Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT,
                        Advertisement::AD_STATUS_ENABLE
                    );
                    /**
                     * End fetch ads blocks
                     */

                    $active_user_ids = $subscription_obj->getActiveUserIds();

                    $item_select_city_query = Item::query();
                    $item_select_city_query->select('items.city_id')
                        ->whereIn('id', $all_item_ids)
                        ->where("items.item_status", Item::ITEM_PUBLISHED)
                        ->where('items.country_id', $site_prefer_country_id)
                        ->where("items.state_id", $state->id)
                        ->whereIn('items.user_id', $active_user_ids)
                        ->groupBy('items.city_id')
                        ->with('city');

                    $all_item_cities = $item_select_city_query->get();

                    /**
                     * Start initial filter
                     */
                    $total_results = $total_paid_items + $total_free_items;
                    /**
                     * End initial filter
                     */

                    /**
                     * Start inner page header customization
                     */
                    if($category->category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_INHERITED)
                    {
                        $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
                            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

                        $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
                            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

                        $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
                            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
                        $site_innerpage_header_background_image = Storage::disk('public')->url('customization/' . $site_innerpage_header_background_image);

                        $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
                            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
                    }
                    else
                    {
                        if($category->category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_COLOR)
                        {
                            $site_innerpage_header_background_type = Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR;
                        }
                        elseif($category->category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_IMAGE)
                        {
                            $site_innerpage_header_background_type = Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE;
                        }
                        elseif($category->category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_VIDEO)
                        {
                            $site_innerpage_header_background_type = Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO;
                        }

                        $site_innerpage_header_background_color = $category->category_header_background_color;

                        $site_innerpage_header_background_image = $category->category_header_background_image;
                        $site_innerpage_header_background_image = Storage::disk('public')->url('category/' . $site_innerpage_header_background_image);

                        $site_innerpage_header_background_youtube_video = $category->category_header_background_youtube_video;
                    }

                    $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
                        ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

                    $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
                        ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
                    /**
                     * End inner page header customization
                     */

                    /**
                     * Start initial blade view file path
                     */
                    $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
                    $theme_view_path = $theme_view_path->getViewPath();
                    /**
                     * End initial blade view file path
                     */

                    return response()->view($theme_view_path . 'category.city',
                        compact('category', 'state', 'city', 'paid_items', 'free_items', 'pagination',
                            'all_item_cities', 'ads_before_breadcrumb', 'ads_after_breadcrumb', 'ads_before_content', 'ads_after_content',
                            'ads_before_sidebar_content', 'ads_after_sidebar_content', 'parent_categories', 'children_categories',
                            'parent_category_id', 'site_innerpage_header_background_type', 'site_innerpage_header_background_color',
                            'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
                            'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color',
                            'filter_sort_by', 'filter_categories', 'total_results'));
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
        else
        {
            abort(404);
        }
    }

    public function state(Request $request, string $state_slug)
    {
        $state = State::where('state_slug', $state_slug)->first();

        if($state)
        {
            $settings = app('site_global_settings');
            $site_prefer_country_id = app('site_prefer_country_id');

            /**
             * Start SEO
             */
            SEOMeta::setTitle(__('seo.frontend.categories-state', ['state_name' => $state->state_name, 'site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
            SEOMeta::setDescription('');
            SEOMeta::setCanonical(URL::current());
            SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
            /**
             * End SEO
             */

            /**
             * Do listing query
             * 1. get paid listings and free listings.
             * 2. decide how many paid and free listings per page and total pages.
             * 3. decide the pagination to paid or free listings
             * 4. run query and render
             */

            // paid listing
            $paid_items_query = Item::query();

            /**
             * Start filter for paid listing
             */
            // categories
            $filter_categories = empty($request->filter_categories) ? array() : $request->filter_categories;

            $category_obj = new Category();
            $item_ids = $category_obj->getItemIdsByCategoryIds($filter_categories);

            // city
            $filter_city = empty($request->filter_city) ? null : $request->filter_city;
            /**
             * End filter for paid listing
             */

            // get paid users id array
            $subscription_obj = new Subscription();
            $paid_user_ids = $subscription_obj->getPaidUserIds();

            if(count($item_ids) > 0)
            {
                $paid_items_query->whereIn('id', $item_ids);
            }

            $paid_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
                ->where('items.country_id', $site_prefer_country_id)
                ->where("items.state_id", $state->id)
                ->where('items.item_featured', Item::ITEM_FEATURED)
                ->where(function($query) use ($paid_user_ids) {

                    $query->whereIn('items.user_id', $paid_user_ids)
                        ->orWhere('items.item_featured_by_admin', Item::ITEM_FEATURED_BY_ADMIN);
                });


            // filter paid listings city
            if(!empty($filter_city))
            {
                $paid_items_query->where('items.city_id', $filter_city);
            }

            $paid_items_query->orderBy('items.created_at', 'DESC')
                ->distinct('items.id')
                ->with('state')
                ->with('city')
                ->with('user');

            $total_paid_items = $paid_items_query->count();

            // free listing
            $free_items_query = Item::query();

            // get free users id array
            //$free_user_ids = $subscription_obj->getFreeUserIds();
            $free_user_ids = $subscription_obj->getActiveUserIds();

            if(count($item_ids) > 0)
            {
                $free_items_query->whereIn('id', $item_ids);
            }

            $free_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
                ->where('items.country_id', $site_prefer_country_id)
                ->where("items.state_id", $state->id)
                ->where('items.item_featured', Item::ITEM_NOT_FEATURED)
                ->where('items.item_featured_by_admin', Item::ITEM_NOT_FEATURED_BY_ADMIN)
                ->whereIn('items.user_id', $free_user_ids);

            // filter free listings city
            if(!empty($filter_city))
            {
                $free_items_query->where('items.city_id', $filter_city);
            }

            /**
             * Start filter sort by for free listing
             */
            $filter_sort_by = empty($request->filter_sort_by) ? Item::ITEMS_SORT_BY_NEWEST_CREATED : $request->filter_sort_by;
            if($filter_sort_by == Item::ITEMS_SORT_BY_NEWEST_CREATED)
            {
                $free_items_query->orderBy('items.created_at', 'DESC');
            }
            elseif($filter_sort_by == Item::ITEMS_SORT_BY_OLDEST_CREATED)
            {
                $free_items_query->orderBy('items.created_at', 'ASC');
            }
            elseif($filter_sort_by == Item::ITEMS_SORT_BY_HIGHEST_RATING)
            {
                $free_items_query->orderBy('items.item_average_rating', 'DESC');
            }
            elseif($filter_sort_by == Item::ITEMS_SORT_BY_LOWEST_RATING)
            {
                $free_items_query->orderBy('items.item_average_rating', 'ASC');
            }
            elseif($filter_sort_by == Item::ITEMS_SORT_BY_NEARBY_FIRST)
            {
                $free_items_query->selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( item_lat ) ) * cos( radians( item_lng ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( item_lat ) ) ) ) AS distance', [$this->getLatitude(), $this->getLongitude(), $this->getLatitude()])
                    ->orderBy('distance', 'ASC');
            }
            /**
             * End filter sort by for free listing
             */

            $free_items_query->distinct('items.id')
                ->with('state')
                ->with('city')
                ->with('user');

            $total_free_items = $free_items_query->count();

            $querystringArray = [
                'filter_categories' => $filter_categories,
                'filter_sort_by' => $filter_sort_by,
                'filter_city' => $filter_city,
            ];

            if($total_free_items == 0 || $total_paid_items == 0)
            {
                $paid_items = $paid_items_query->paginate(10);
                $free_items = $free_items_query->paginate(10);

                if($total_free_items == 0)
                {
                    $pagination = $paid_items->appends($querystringArray);
                }
                if($total_paid_items == 0)
                {
                    $pagination = $free_items->appends($querystringArray);
                }
            }
            else
            {
                $num_of_pages = ceil(($total_paid_items + $total_free_items) / 10);
                $paid_items_per_page = ceil($total_paid_items / $num_of_pages) < 4 ? 4 : ceil($total_paid_items / $num_of_pages);
                $free_items_per_page = 10 - $paid_items_per_page;

                $paid_items = $paid_items_query->paginate($paid_items_per_page);
                $free_items = $free_items_query->paginate($free_items_per_page);

                if(ceil($total_paid_items / $paid_items_per_page) > ceil($total_free_items / $free_items_per_page))
                {
                    $pagination = $paid_items->appends($querystringArray);
                }
                else
                {
                    $pagination = $free_items->appends($querystringArray);
                }
            }
            /**
             * End do listing query
             */

            /**
             * Start fetch ads blocks
             */
            $advertisement = new Advertisement();

            $ads_before_breadcrumb = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_BEFORE_BREADCRUMB,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_breadcrumb = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_AFTER_BREADCRUMB,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_BEFORE_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_AFTER_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_sidebar_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_sidebar_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );
            /**
             * End fetch ads blocks
             */

            $active_user_ids = $subscription_obj->getActiveUserIds();

            $item_select_city_query = Item::query();
            $item_select_city_query->select('items.city_id')
                ->where("items.item_status", Item::ITEM_PUBLISHED)
                ->where('items.country_id', $site_prefer_country_id)
                ->where("items.state_id", $state->id)
                ->whereIn('items.user_id', $active_user_ids)
                ->groupBy('items.city_id')
                ->with('city');

            $all_item_cities = $item_select_city_query->get();

            /**
             * Start inner page header customization
             */
            $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
            /**
             * End inner page header customization
             */

            /**
             * Start initial filter
             */
            $all_printable_categories = $category_obj->getPrintableCategoriesNoDash();

            $filter_all_cities = $state->cities()->orderBy('city_name')->get();

            $total_results = $total_paid_items + $total_free_items;
            /**
             * End initial filter
             */

            /**
             * Start initial blade view file path
             */
            $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
            $theme_view_path = $theme_view_path->getViewPath();
            /**
             * End initial blade view file path
             */

            return response()->view($theme_view_path . 'state',
                compact('state', 'paid_items', 'free_items', 'pagination', 'all_item_cities',
                    'ads_before_breadcrumb', 'ads_after_breadcrumb', 'ads_before_content', 'ads_after_content',
                    'ads_before_sidebar_content', 'ads_after_sidebar_content', 'site_innerpage_header_background_type',
                    'site_innerpage_header_background_color', 'site_innerpage_header_background_image',
                    'site_innerpage_header_background_youtube_video', 'site_innerpage_header_title_font_color',
                    'site_innerpage_header_paragraph_font_color', 'filter_categories', 'filter_all_cities', 'filter_city',
                    'filter_sort_by', 'total_results', 'all_printable_categories'));
        }
        else
        {
            abort(404);
        }
    }

    public function city(Request $request, string $state_slug, string $city_slug)
    {
        $state = State::where('state_slug', $state_slug)->first();

        if($state)
        {
            $city = $state->cities()->where('city_slug', $city_slug)->first();

            if($city)
            {
                $settings = app('site_global_settings');
                $site_prefer_country_id = app('site_prefer_country_id');

                /**
                 * Start SEO
                 */
                SEOMeta::setTitle(__('seo.frontend.categories-state-city', ['state_name' => $state->state_name, 'city_name' => $city->city_name, 'site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
                SEOMeta::setDescription('');
                SEOMeta::setCanonical(URL::current());
                SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
                /**
                 * End SEO
                 */

                /**
                 * Do listing query
                 * 1. get paid listings and free listings.
                 * 2. decide how many paid and free listings per page and total pages.
                 * 3. decide the pagination to paid or free listings
                 * 4. run query and render
                 */

                // paid listing
                $paid_items_query = Item::query();

                /**
                 * Start filter for paid listing
                 */
                // categories
                $filter_categories = empty($request->filter_categories) ? array() : $request->filter_categories;

                $category_obj = new Category();
                $item_ids = $category_obj->getItemIdsByCategoryIds($filter_categories);
                /**
                 * End filter for paid listing
                 */

                // get paid users id array
                $subscription_obj = new Subscription();
                $paid_user_ids = $subscription_obj->getPaidUserIds();

                if(count($item_ids) > 0)
                {
                    $paid_items_query->whereIn('id', $item_ids);
                }

                $paid_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
                    ->where('items.country_id', $site_prefer_country_id)
                    ->where("items.state_id", $state->id)
                    ->where("items.city_id", $city->id)
                    ->where('items.item_featured', Item::ITEM_FEATURED)
                    ->where(function($query) use ($paid_user_ids) {

                        $query->whereIn('items.user_id', $paid_user_ids)
                            ->orWhere('items.item_featured_by_admin', Item::ITEM_FEATURED_BY_ADMIN);
                    });

                $paid_items_query->orderBy('items.created_at', 'DESC')
                    ->distinct('items.id')
                    ->with('state')
                    ->with('city')
                    ->with('user');

                $total_paid_items = $paid_items_query->count();

                // free listing
                $free_items_query = Item::query();

                // get free users id array
                //$free_user_ids = $subscription_obj->getFreeUserIds();
                $free_user_ids = $subscription_obj->getActiveUserIds();

                if(count($item_ids) > 0)
                {
                    $free_items_query->whereIn('id', $item_ids);
                }

                $free_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
                    ->where('items.country_id', $site_prefer_country_id)
                    ->where("items.state_id", $state->id)
                    ->where("items.city_id", $city->id)
                    ->where('items.item_featured', Item::ITEM_NOT_FEATURED)
                    ->where('items.item_featured_by_admin', Item::ITEM_NOT_FEATURED_BY_ADMIN)
                    ->whereIn('items.user_id', $free_user_ids);

                /**
                 * Start filter sort by for free listing
                 */
                $filter_sort_by = empty($request->filter_sort_by) ? Item::ITEMS_SORT_BY_NEWEST_CREATED : $request->filter_sort_by;
                if($filter_sort_by == Item::ITEMS_SORT_BY_NEWEST_CREATED)
                {
                    $free_items_query->orderBy('items.created_at', 'DESC');
                }
                elseif($filter_sort_by == Item::ITEMS_SORT_BY_OLDEST_CREATED)
                {
                    $free_items_query->orderBy('items.created_at', 'ASC');
                }
                elseif($filter_sort_by == Item::ITEMS_SORT_BY_HIGHEST_RATING)
                {
                    $free_items_query->orderBy('items.item_average_rating', 'DESC');
                }
                elseif($filter_sort_by == Item::ITEMS_SORT_BY_LOWEST_RATING)
                {
                    $free_items_query->orderBy('items.item_average_rating', 'ASC');
                }
                elseif($filter_sort_by == Item::ITEMS_SORT_BY_NEARBY_FIRST)
                {
                    $free_items_query->selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( item_lat ) ) * cos( radians( item_lng ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( item_lat ) ) ) ) AS distance', [$this->getLatitude(), $this->getLongitude(), $this->getLatitude()])
                        ->orderBy('distance', 'ASC');
                }
                /**
                 * End filter sort by for free listing
                 */

                $free_items_query->distinct('items.id')
                    ->with('state')
                    ->with('city')
                    ->with('user');

                $total_free_items = $free_items_query->count();

                $querystringArray = [
                    'filter_categories' => $filter_categories,
                    'filter_sort_by' => $filter_sort_by,
                ];

                if($total_free_items == 0 || $total_paid_items == 0)
                {
                    $paid_items = $paid_items_query->paginate(10);
                    $free_items = $free_items_query->paginate(10);

                    if($total_free_items == 0)
                    {
                        $pagination = $paid_items->appends($querystringArray);
                    }
                    if($total_paid_items == 0)
                    {
                        $pagination = $free_items->appends($querystringArray);
                    }
                }
                else
                {
                    $num_of_pages = ceil(($total_paid_items + $total_free_items) / 10);
                    $paid_items_per_page = ceil($total_paid_items / $num_of_pages) < 4 ? 4 : ceil($total_paid_items / $num_of_pages);
                    $free_items_per_page = 10 - $paid_items_per_page;

                    $paid_items = $paid_items_query->paginate($paid_items_per_page);
                    $free_items = $free_items_query->paginate($free_items_per_page);

                    if(ceil($total_paid_items / $paid_items_per_page) > ceil($total_free_items / $free_items_per_page))
                    {
                        $pagination = $paid_items->appends($querystringArray);
                    }
                    else
                    {
                        $pagination = $free_items->appends($querystringArray);
                    }
                }
                /**
                 * End do listing query
                 */

                /**
                 * Start fetch ads blocks
                 */
                $advertisement = new Advertisement();

                $ads_before_breadcrumb = $advertisement->fetchAdvertisements(
                    Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                    Advertisement::AD_POSITION_BEFORE_BREADCRUMB,
                    Advertisement::AD_STATUS_ENABLE
                );

                $ads_after_breadcrumb = $advertisement->fetchAdvertisements(
                    Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                    Advertisement::AD_POSITION_AFTER_BREADCRUMB,
                    Advertisement::AD_STATUS_ENABLE
                );

                $ads_before_content = $advertisement->fetchAdvertisements(
                    Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                    Advertisement::AD_POSITION_BEFORE_CONTENT,
                    Advertisement::AD_STATUS_ENABLE
                );

                $ads_after_content = $advertisement->fetchAdvertisements(
                    Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                    Advertisement::AD_POSITION_AFTER_CONTENT,
                    Advertisement::AD_STATUS_ENABLE
                );

                $ads_before_sidebar_content = $advertisement->fetchAdvertisements(
                    Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                    Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT,
                    Advertisement::AD_STATUS_ENABLE
                );

                $ads_after_sidebar_content = $advertisement->fetchAdvertisements(
                    Advertisement::AD_PLACE_LISTING_RESULTS_PAGES,
                    Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT,
                    Advertisement::AD_STATUS_ENABLE
                );
                /**
                 * End fetch ads blocks
                 */

                $active_user_ids = $subscription_obj->getActiveUserIds();

                $item_select_city_query = Item::query();
                $item_select_city_query->select('items.city_id')
                    ->where("items.item_status", Item::ITEM_PUBLISHED)
                    ->where('items.country_id', $site_prefer_country_id)
                    ->where("items.state_id", $state->id)
                    ->whereIn('items.user_id', $active_user_ids)
                    ->groupBy('items.city_id')
                    ->with('city');

                $all_item_cities = $item_select_city_query->get();

                /**
                 * Start inner page header customization
                 */
                $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
                    ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

                $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
                    ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

                $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
                    ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

                $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
                    ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

                $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
                    ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

                $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
                    ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
                /**
                 * End inner page header customization
                 */

                /**
                 * Start initial filter
                 */
                $all_printable_categories = $category_obj->getPrintableCategoriesNoDash();

                $total_results = $total_paid_items + $total_free_items;
                /**
                 * End initial filter
                 */

                /**
                 * Start initial blade view file path
                 */
                $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
                $theme_view_path = $theme_view_path->getViewPath();
                /**
                 * End initial blade view file path
                 */

                return response()->view($theme_view_path . 'city',
                    compact('state','city', 'paid_items', 'free_items', 'pagination', 'all_item_cities',
                        'ads_before_breadcrumb', 'ads_after_breadcrumb', 'ads_before_content', 'ads_after_content',
                        'ads_before_sidebar_content', 'ads_after_sidebar_content', 'site_innerpage_header_background_type',
                        'site_innerpage_header_background_color', 'site_innerpage_header_background_image',
                        'site_innerpage_header_background_youtube_video', 'site_innerpage_header_title_font_color',
                        'site_innerpage_header_paragraph_font_color', 'filter_categories', 'filter_sort_by', 'total_results',
                        'all_printable_categories'));
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

    public function product(Request $request, string $item_slug, string $product_slug)
    {
        $settings = app('site_global_settings');

        $item = Item::where('item_slug', $item_slug)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->first();

        if($item)
        {
            // validate product record
            $product = Product::where('product_slug', $product_slug)
                ->where('product_status', Product::STATUS_APPROVED)
                ->first();

            if($product)
            {
                // validate if the item has collected the product in the listing page
                if($item->hasCollectedProduct($product))
                {
                    /**
                     * Start SEO
                     */
                    SEOMeta::setTitle($product->product_name . ' - ' . $item->item_title . ' - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
                    SEOMeta::setDescription($product->product_description);
                    SEOMeta::setCanonical(URL::current());
                    SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);

                    // OpenGraph
                    OpenGraph::setTitle($product->product_name . ' - ' . $item->item_title . ' - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
                    OpenGraph::setDescription($product->product_description);
                    OpenGraph::setUrl(URL::current());
                    if(empty($product->product_image_large))
                    {
                        OpenGraph::addImage(asset('frontend/images/placeholder/full_item_feature_image.webp'));
                    }
                    else
                    {
                        OpenGraph::addImage(Storage::disk('public')->url('product/' . $product->product_image_large));
                    }

                    // Twitter
                    TwitterCard::setTitle($product->product_name . ' - ' . $item->item_title . ' - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
                    /**
                     * End SEO
                     */

                    $item_display_categories = $item->getAllCategories(Item::ITEM_TOTAL_SHOW_CATEGORY);
                    $item_total_categories = $item->allCategories()->count();
                    $item_all_categories = $item->getAllCategories();

                    $item_count_rating = $item->getCountRating();
                    $item_average_rating = $item->item_average_rating;

                    $product_features = $product->productFeatures()
                        ->orderBy('product_feature_order')
                        ->get();

                    /**
                     * Start initial opening hours
                     */
                    $opening_hours_array_monday = array();
                    $opening_hours_array_tuesday = array();
                    $opening_hours_array_wednesday = array();
                    $opening_hours_array_thursday = array();
                    $opening_hours_array_friday = array();
                    $opening_hours_array_saturday = array();
                    $opening_hours_array_sunday = array();
                    $opening_hour_exceptions_array = array();

                    $item_hours = $item->itemHours()->get();
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

                    $item_hour_exceptions = $item->itemHourExceptions()->get();
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
                    ], $item->item_hour_time_zone);

                    $datetime_now = new DateTime('now');
                    $current_open_range = $opening_hours_obj->currentOpenRange($datetime_now);

                    $opening_hours_week = $opening_hours_obj->forWeek();
                    $opening_hours_week_monday = $opening_hours_week['monday'];
                    $opening_hours_week_tuesday = $opening_hours_week['tuesday'];
                    $opening_hours_week_wednesday = $opening_hours_week['wednesday'];
                    $opening_hours_week_thursday = $opening_hours_week['thursday'];
                    $opening_hours_week_friday = $opening_hours_week['friday'];
                    $opening_hours_week_saturday = $opening_hours_week['saturday'];
                    $opening_hours_week_sunday = $opening_hours_week['sunday'];

                    $item_hours_monday = $opening_hours_week_monday->getIterator();
                    $item_hours_tuesday = $opening_hours_week_tuesday->getIterator();
                    $item_hours_wednesday = $opening_hours_week_wednesday->getIterator();
                    $item_hours_thursday = $opening_hours_week_thursday->getIterator();
                    $item_hours_friday = $opening_hours_week_friday->getIterator();
                    $item_hours_saturday = $opening_hours_week_saturday->getIterator();
                    $item_hours_sunday = $opening_hours_week_sunday->getIterator();

                    $item_hour_exceptions_obj = $opening_hours_obj->exceptions();
                    /**
                     * End initial opening hours
                     */

                    /**
                     * get 4 nearby items by current item lat and lng
                     */
                    $latitude = $item->item_lat;
                    $longitude = $item->item_lng;

                    $nearby_items = Item::selectRaw('items.*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( item_lat ) ) * cos( radians( item_lng ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( item_lat ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
                        ->where('id', '!=', $item->id)
                        ->where('item_status', Item::ITEM_PUBLISHED)
                        ->where('item_type', Item::ITEM_TYPE_REGULAR)
                        ->orderBy('distance', 'ASC')
                        ->with('state')
                        ->with('city')
                        ->with('user')
                        ->take(4)->get();

                    /**
                     * get 4 similar items by current item lat and lng
                     */
                    $item_category_ids = array();
                    foreach($item_all_categories as $key => $category)
                    {
                        $item_category_ids[] = $category->id;
                    }

                    $category_obj = new Category();
                    $similar_item_ids = $category_obj->getItemIdsByCategoryIds($item_category_ids);

                    $similar_item_ids_length = count($similar_item_ids);
                    if($similar_item_ids_length > Item::ITEM_SIMILAR_SHOW_MAX)
                    {
                        $similar_item_ids = array_slice($similar_item_ids, rand(0, $similar_item_ids_length-Item::ITEM_SIMILAR_SHOW_MAX), Item::ITEM_SIMILAR_SHOW_MAX);
                    }

                    $similar_items = Item::whereIn('items.id', $similar_item_ids)
                        ->where('items.id', '!=', $item->id)
                        ->where('items.item_status', Item::ITEM_PUBLISHED)
                        ->distinct('items.id')
                        ->inRandomOrder()
                        ->limit(Item::ITEM_SIMILAR_SHOW_MAX)
                        ->get();

                    /**
                     * Start item claim
                     */
                    $item_has_claimed = $item->hasClaimed();
                    /**
                     * End item claim
                     */

                    /**
                     * Start fetch ads blocks
                     */
                    $advertisement = new Advertisement();

                    $ads_before_sidebar_content = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE,
                        Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_after_sidebar_content = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE,
                        Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT,
                        Advertisement::AD_STATUS_ENABLE
                    );
                    /**
                     * End fetch ads blocks
                     */


                    /**
                     * Start initial blade view file path
                     */
                    $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
                    $theme_view_path = $theme_view_path->getViewPath();
                    /**
                     * End initial blade view file path
                     */

                    /**
                     * Start initial Google reCAPTCHA version 2
                     */
                    if($settings->setting_site_recaptcha_item_lead_enable == Setting::SITE_RECAPTCHA_ITEM_LEAD_ENABLE)
                    {
                        config_re_captcha($settings->setting_site_recaptcha_site_key, $settings->setting_site_recaptcha_secret_key);
                    }
                    /**
                     * End initial Google reCAPTCHA version 2
                     */

                    return response()->view($theme_view_path . 'product',
                        compact('product', 'item', 'product_features', 'nearby_items', 'similar_items',
                            'ads_before_sidebar_content', 'ads_after_sidebar_content', 'item_display_categories',
                            'item_total_categories', 'item_all_categories', 'item_count_rating', 'item_average_rating',
                            'item_has_claimed', 'opening_hours_obj', 'datetime_now', 'current_open_range', 'item_hours_monday',
                            'item_hours_tuesday', 'item_hours_wednesday', 'item_hours_thursday', 'item_hours_friday',
                            'item_hours_saturday', 'item_hours_sunday', 'item_hour_exceptions_obj', 'item_hours',
                            'item_hour_exceptions'));
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
        else
        {
            abort(404);
        }
    }

    /**
     * @param Request $request
     * @param string $item_slug
     * @return Response
     */
    public function item(Request $request, string $item_slug)
    {
        $settings = app('site_global_settings');

        $item = Item::where('item_slug', $item_slug)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->first();

        if($item)
        {
            $item_user = $item->user()->first();

            if($item_user)
            {
                if($item_user->hasActive())
                {
                    /**
                     * Start SEO
                     */
                    SEOMeta::setTitle($item->item_title . ' - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
                    SEOMeta::setDescription($item->item_description);
                    SEOMeta::setCanonical(URL::current());
                    SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);

                    // OpenGraph
                    OpenGraph::setTitle($item->item_title . ' - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
                    OpenGraph::setDescription($item->item_description);
                    OpenGraph::setUrl(URL::current());
                    if(empty($item->item_image))
                    {
                        OpenGraph::addImage(asset('frontend/images/placeholder/full_item_feature_image.webp'));
                    }
                    else
                    {
                        OpenGraph::addImage(Storage::disk('public')->url('item/' . $item->item_image));
                    }

                    // Twitter
                    TwitterCard::setTitle($item->item_title . ' - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
                    /**
                     * End SEO
                     */

                    $item_display_categories = $item->getAllCategories(Item::ITEM_TOTAL_SHOW_CATEGORY);
                    $item_total_categories = $item->allCategories()->count();
                    $item_all_categories = $item->getAllCategories();

                    /**
                     * Start initla item features
                     */
                    $item_features = $item->features()->where('item_feature_value', '<>', '')
                        ->whereNotNull('item_feature_value')
                        ->get();
                    /**
                     * End initial item features
                     */

                    /**
                     * Start initial opening hours
                     */
                    $opening_hours_array_monday = array();
                    $opening_hours_array_tuesday = array();
                    $opening_hours_array_wednesday = array();
                    $opening_hours_array_thursday = array();
                    $opening_hours_array_friday = array();
                    $opening_hours_array_saturday = array();
                    $opening_hours_array_sunday = array();
                    $opening_hour_exceptions_array = array();

                    $item_hours = $item->itemHours()->get();
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

                    $item_hour_exceptions = $item->itemHourExceptions()->get();
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
                    ], $item->item_hour_time_zone);

                    $datetime_now = new DateTime('now');
                    $current_open_range = $opening_hours_obj->currentOpenRange($datetime_now);

                    $opening_hours_week = $opening_hours_obj->forWeek();
                    $opening_hours_week_monday = $opening_hours_week['monday'];
                    $opening_hours_week_tuesday = $opening_hours_week['tuesday'];
                    $opening_hours_week_wednesday = $opening_hours_week['wednesday'];
                    $opening_hours_week_thursday = $opening_hours_week['thursday'];
                    $opening_hours_week_friday = $opening_hours_week['friday'];
                    $opening_hours_week_saturday = $opening_hours_week['saturday'];
                    $opening_hours_week_sunday = $opening_hours_week['sunday'];

                    $item_hours_monday = $opening_hours_week_monday->getIterator();
                    $item_hours_tuesday = $opening_hours_week_tuesday->getIterator();
                    $item_hours_wednesday = $opening_hours_week_wednesday->getIterator();
                    $item_hours_thursday = $opening_hours_week_thursday->getIterator();
                    $item_hours_friday = $opening_hours_week_friday->getIterator();
                    $item_hours_saturday = $opening_hours_week_saturday->getIterator();
                    $item_hours_sunday = $opening_hours_week_sunday->getIterator();

                    $item_hour_exceptions_obj = $opening_hours_obj->exceptions();
                    /**
                     * End initial opening hours
                     */

                    /**
                     * get 4 nearby items by current item lat and lng
                     */
                    $latitude = $item->item_lat;
                    $longitude = $item->item_lng;

                    $nearby_items = Item::selectRaw('items.*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( item_lat ) ) * cos( radians( item_lng ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( item_lat ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
                        ->where('id', '!=', $item->id)
                        ->where('item_status', Item::ITEM_PUBLISHED)
                        ->where('item_type', Item::ITEM_TYPE_REGULAR)
                        ->orderBy('distance', 'ASC')
                        ->with('state')
                        ->with('city')
                        ->with('user')
                        ->take(4)->get();

                    /**
                     * get 4 similar items by current item lat and lng
                     */
                    $item_category_ids = array();
                    foreach($item_all_categories as $item_all_categories_key => $category)
                    {
                        $item_category_ids[] = $category->id;
                    }

                    $category_obj = new Category();
                    $similar_item_ids = $category_obj->getItemIdsByCategoryIds($item_category_ids);

                    $similar_item_ids_length = count($similar_item_ids);
                    if($similar_item_ids_length > Item::ITEM_SIMILAR_SHOW_MAX)
                    {
                        $similar_item_ids = array_slice($similar_item_ids, rand(0, $similar_item_ids_length-Item::ITEM_SIMILAR_SHOW_MAX), Item::ITEM_SIMILAR_SHOW_MAX);
                    }

                    $similar_items = Item::whereIn('items.id', $similar_item_ids)
                        ->where('items.id', '!=', $item->id)
                        ->where('items.item_status', Item::ITEM_PUBLISHED)
                        ->distinct('items.id')
                        ->inRandomOrder()
                        ->limit(Item::ITEM_SIMILAR_SHOW_MAX)
                        ->get();

                    /**
                     * Start get all item approved reviews
                     */
                    $item_count_rating = $item->getCountRating();
                    $item_average_rating = $item->item_average_rating;

                    $rating_sort_by = empty($request->rating_sort_by) ? Item::ITEM_RATING_SORT_BY_NEWEST : $request->rating_sort_by;
                    $reviews = $item->getApprovedRatingsSortBy($rating_sort_by);

                    if($item_count_rating > 0)
                    {
                        $item_one_star_count_rating = $item->getStarsCountRating(Item::ITEM_REVIEW_RATING_ONE);
                        $item_two_star_count_rating = $item->getStarsCountRating(Item::ITEM_REVIEW_RATING_TWO);
                        $item_three_star_count_rating = $item->getStarsCountRating(Item::ITEM_REVIEW_RATING_THREE);
                        $item_four_star_count_rating = $item->getStarsCountRating(Item::ITEM_REVIEW_RATING_FOUR);
                        $item_five_star_count_rating = $item->getStarsCountRating(Item::ITEM_REVIEW_RATING_FIVE);

                        $item_one_star_percentage = ($item_one_star_count_rating / $item_count_rating) * 100;
                        $item_two_star_percentage = ($item_two_star_count_rating / $item_count_rating) * 100;
                        $item_three_star_percentage = ($item_three_star_count_rating / $item_count_rating) * 100;
                        $item_four_star_percentage = ($item_four_star_count_rating / $item_count_rating) * 100;
                        $item_five_star_percentage = ($item_five_star_count_rating / $item_count_rating) * 100;
                    }
                    else
                    {
                        $item_one_star_percentage = 0;
                        $item_two_star_percentage = 0;
                        $item_three_star_percentage = 0;
                        $item_four_star_percentage = 0;
                        $item_five_star_percentage = 0;
                    }
                    /**
                     * End get all item approved reviews
                     */

                    /**
                     * Start item claim
                     */
                    $item_has_claimed = $item->hasClaimed();
                    /**
                     * End item claim
                     */

                    /**
                     * Start fetch ads blocks
                     */
                    $advertisement = new Advertisement();

                    $ads_before_breadcrumb = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE,
                        Advertisement::AD_POSITION_BEFORE_BREADCRUMB,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_after_breadcrumb = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE,
                        Advertisement::AD_POSITION_AFTER_BREADCRUMB,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_before_gallery = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE,
                        Advertisement::AD_POSITION_BEFORE_GALLERY,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_before_description = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE,
                        Advertisement::AD_POSITION_BEFORE_DESCRIPTION,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_before_location = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE,
                        Advertisement::AD_POSITION_BEFORE_LOCATION,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_before_features = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE,
                        Advertisement::AD_POSITION_BEFORE_FEATURES,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_before_reviews = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE,
                        Advertisement::AD_POSITION_BEFORE_REVIEWS,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_before_comments = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE,
                        Advertisement::AD_POSITION_BEFORE_COMMENTS,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_before_share = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE,
                        Advertisement::AD_POSITION_BEFORE_SHARE,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_after_share = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE,
                        Advertisement::AD_POSITION_AFTER_SHARE,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_before_sidebar_content = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE,
                        Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT,
                        Advertisement::AD_STATUS_ENABLE
                    );

                    $ads_after_sidebar_content = $advertisement->fetchAdvertisements(
                        Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE,
                        Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT,
                        Advertisement::AD_STATUS_ENABLE
                    );
                    /**
                     * End fetch ads blocks
                     */

                    /**
                     * Start fetch item sections
                     */
                    $item_sections_after_breadcrumb = $item->itemSections()
                        ->where('item_section_position', ItemSection::POSITION_AFTER_BREADCRUMB)
                        ->where('item_section_status', ItemSection::STATUS_PUBLISHED)
                        ->orderBy('item_section_order')
                        ->get();

                    $item_sections_after_gallery = $item->itemSections()
                        ->where('item_section_position', ItemSection::POSITION_AFTER_GALLERY)
                        ->where('item_section_status', ItemSection::STATUS_PUBLISHED)
                        ->orderBy('item_section_order')
                        ->get();

                    $item_sections_after_description = $item->itemSections()
                        ->where('item_section_position', ItemSection::POSITION_AFTER_DESCRIPTION)
                        ->where('item_section_status', ItemSection::STATUS_PUBLISHED)
                        ->orderBy('item_section_order')
                        ->get();

                    $item_sections_after_location_map = $item->itemSections()
                        ->where('item_section_position', ItemSection::POSITION_AFTER_LOCATION_MAP)
                        ->where('item_section_status', ItemSection::STATUS_PUBLISHED)
                        ->orderBy('item_section_order')
                        ->get();

                    $item_sections_after_features = $item->itemSections()
                        ->where('item_section_position', ItemSection::POSITION_AFTER_FEATURES)
                        ->where('item_section_status', ItemSection::STATUS_PUBLISHED)
                        ->orderBy('item_section_order')
                        ->get();

                    $item_sections_after_reviews = $item->itemSections()
                        ->where('item_section_position', ItemSection::POSITION_AFTER_REVIEWS)
                        ->where('item_section_status', ItemSection::STATUS_PUBLISHED)
                        ->orderBy('item_section_order')
                        ->get();

                    $item_sections_after_comments = $item->itemSections()
                        ->where('item_section_position', ItemSection::POSITION_AFTER_COMMENTS)
                        ->where('item_section_status', ItemSection::STATUS_PUBLISHED)
                        ->orderBy('item_section_order')
                        ->get();

                    $item_sections_after_share = $item->itemSections()
                        ->where('item_section_position', ItemSection::POSITION_AFTER_SHARE)
                        ->where('item_section_status', ItemSection::STATUS_PUBLISHED)
                        ->orderBy('item_section_order')
                        ->get();
                    /**
                     * End fetch item sections
                     */


                    /**
                     * Start initial blade view file path
                     */
                    $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
                    $theme_view_path = $theme_view_path->getViewPath();
                    /**
                     * End initial blade view file path
                     */

                    /**
                     * Start initial Google reCAPTCHA version 2
                     */
                    if($settings->setting_site_recaptcha_item_lead_enable == Setting::SITE_RECAPTCHA_ITEM_LEAD_ENABLE)
                    {
                        config_re_captcha($settings->setting_site_recaptcha_site_key, $settings->setting_site_recaptcha_secret_key);
                    }
                    /**
                     * End initial Google reCAPTCHA version 2
                     */

                    return response()->view($theme_view_path . 'item',
                        compact('item', 'nearby_items', 'similar_items',
                            'reviews', 'ads_before_breadcrumb', 'ads_after_breadcrumb', 'ads_before_gallery', 'ads_before_description',
                            'ads_before_location', 'ads_before_features', 'ads_before_reviews', 'ads_before_comments',
                            'ads_before_share', 'ads_after_share', 'ads_before_sidebar_content', 'ads_after_sidebar_content',
                            'item_display_categories', 'item_total_categories', 'item_all_categories', 'item_count_rating',
                            'item_average_rating', 'item_one_star_percentage', 'item_two_star_percentage', 'item_three_star_percentage',
                            'item_four_star_percentage', 'item_five_star_percentage', 'rating_sort_by', 'item_has_claimed',
                            'item_sections_after_breadcrumb', 'item_sections_after_gallery', 'item_sections_after_description',
                            'item_sections_after_location_map', 'item_sections_after_features', 'item_sections_after_reviews',
                            'item_sections_after_comments', 'item_sections_after_share', 'item_features', 'opening_hours_obj', 'datetime_now',
                            'current_open_range', 'item_hours_monday', 'item_hours_tuesday', 'item_hours_wednesday', 'item_hours_thursday',
                            'item_hours_friday', 'item_hours_saturday', 'item_hours_sunday', 'item_hour_exceptions_obj', 'item_hours',
                            'item_hour_exceptions'));
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
        else
        {
            abort(404);
        }
    }

    public function storeItemLead(Request $request, string $item_slug)
    {
        $item = Item::where('item_slug', $item_slug)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->first();

        if($item)
        {
            $settings = app('site_global_settings');

            if($settings->setting_site_recaptcha_item_lead_enable == Setting::SITE_RECAPTCHA_ITEM_LEAD_ENABLE)
            {
                /**
                 * Start initial Google reCAPTCHA version 2
                 */
                config_re_captcha($settings->setting_site_recaptcha_site_key, $settings->setting_site_recaptcha_secret_key);
                /**
                 * End initial Google reCAPTCHA version 2
                 */

                $request->validate([
                    'item_lead_name' => 'required|max:255',
                    'item_lead_email' => 'required|email',
                    'item_lead_phone' => 'nullable|numeric',
                    'item_lead_subject' => 'nullable|max:255',
                    'item_lead_message' => 'nullable|max:255',
                    'g-recaptcha-response' => 'recaptcha',
                ]);
            }
            else
            {
                $request->validate([
                    'item_lead_name' => 'required|max:255',
                    'item_lead_email' => 'required|email',
                    'item_lead_phone' => 'nullable|numeric',
                    'item_lead_subject' => 'nullable|max:255',
                    'item_lead_message' => 'nullable|max:255',
                ]);
            }

            $item_lead = new ItemLead(array(
                'item_id' => $item->id,
                'item_lead_name' => $request->item_lead_name,
                'item_lead_email' => $request->item_lead_email,
                'item_lead_phone' => $request->item_lead_phone,
                'item_lead_subject' => $request->item_lead_subject,
                'item_lead_message' => $request->item_lead_message,
            ));
            $item_lead->save();

            /**
             * Start email notification
             */
            if($settings->settings_site_smtp_enabled == Setting::SITE_SMTP_ENABLED)
            {
                // config SMTP
                config_smtp(
                    $settings->settings_site_smtp_sender_name,
                    $settings->settings_site_smtp_sender_email,
                    $settings->settings_site_smtp_host,
                    $settings->settings_site_smtp_port,
                    $settings->settings_site_smtp_encryption,
                    $settings->settings_site_smtp_username,
                    $settings->settings_site_smtp_password
                );
            }

            if(!empty($settings->setting_site_name))
            {
                // set up APP_NAME
                config([
                    'app.name' => $settings->setting_site_name,
                ]);
            }

            $email_admin = User::getAdmin();
            $email_user = $item->user()->first();
            $email_subject = __('role_permission.item-leads.email.subject');
            $email_notify_message_user = [
                __('role_permission.item-leads.email.description-user'),
            ];
            $email_notify_message_admin = [
                __('role_permission.item-leads.email.description-admin'),
            ];
            $email_notify_action_text = __('role_permission.item-leads.email.action-text');

            try
            {
                Mail::to($email_admin)->send(
                    new Notification(
                        $email_subject,
                        $email_admin->name,
                        null,
                        $email_notify_message_admin,
                        $email_notify_action_text,
                        'success',
                        route('admin.item-leads.index')
                    )
                );

                if($email_user)
                {
                    Mail::to($email_user)->send(
                        new Notification(
                            $email_subject,
                            $email_user->name,
                            null,
                            $email_notify_message_user,
                            $email_notify_action_text,
                            'success',
                            route('user.item-leads.index')
                        )
                    );
                }
            }
            catch (\Exception $e)
            {
                Log::error($e->getMessage() . "\n" . $e->getTraceAsString());
            }
            /**
             * End email notification
             */

            \Session::flash('flash_message', __('role_permission.item-leads.alert.contact-form-submitted'));
            \Session::flash('flash_type', 'success');

            return back();
        }
        else
        {
            abort(404);
        }
    }

    public function emailItem(string $item_slug, Request $request)
    {
        $settings = app('site_global_settings');

        $item = Item::where('item_slug', $item_slug)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->first();

        if($item)
        {
            if(Auth::check())
            {
                $request->validate([
                    'item_share_email_name' => 'required|max:255',
                    'item_share_email_from_email' => 'required|email|max:255',
                    'item_share_email_to_email' => 'required|email|max:255',
                ]);

                // send an email notification to admin
                $email_to = $request->item_share_email_to_email;
                $email_from_name = $request->item_share_email_name;
                $email_note = $request->item_share_email_note;
                $email_subject = __('frontend.item.send-email-subject', ['name' => $email_from_name]);

                $email_notify_message = [
                    __('frontend.item.send-email-body', ['from_name' => $email_from_name, 'url' => route('page.item', $item->item_slug)]),
                    __('frontend.item.send-email-note'),
                    $email_note,
                ];

                /**
                 * Start initial SMTP settings
                 */
                if($settings->settings_site_smtp_enabled == Setting::SITE_SMTP_ENABLED)
                {
                    // config SMTP
                    config_smtp(
                        $settings->settings_site_smtp_sender_name,
                        $settings->settings_site_smtp_sender_email,
                        $settings->settings_site_smtp_host,
                        $settings->settings_site_smtp_port,
                        $settings->settings_site_smtp_encryption,
                        $settings->settings_site_smtp_username,
                        $settings->settings_site_smtp_password
                    );
                }
                /**
                 * End initial SMTP settings
                 */

                if(!empty($settings->setting_site_name))
                {
                    // set up APP_NAME
                    config([
                        'app.name' => $settings->setting_site_name,
                    ]);
                }

                try
                {
                    // to admin
                    Mail::to($email_to)->send(
                        new Notification(
                            $email_subject,
                            $email_to,
                            null,
                            $email_notify_message,
                            __('frontend.item.view-listing'),
                            'success',
                            route('page.item', $item->item_slug)
                        )
                    );

                    \Session::flash('flash_message', __('frontend.item.send-email-success'));
                    \Session::flash('flash_type', 'success');

                }
                catch (\Exception $e)
                {
                    Log::error($e->getMessage() . "\n" . $e->getTraceAsString());

                    \Session::flash('flash_message', __('theme_directory_hub.email.alert.sending-problem'));
                    \Session::flash('flash_type', 'danger');
                }

                return redirect()->route('page.item', $item->item_slug);
            }
            else
            {
                \Session::flash('flash_message', __('frontend.item.send-email-error-login'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('page.item', $item->item_slug);
            }
        }
        else
        {
            abort(404);
        }

    }

    public function saveItem(Request $request, string $item_slug)
    {
        $item = Item::where('item_slug', $item_slug)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->first();

        if($item)
        {
            if(Auth::check())
            {
                $login_user = Auth::user();

                if($login_user->hasSavedItem($item->id))
                {
                    \Session::flash('flash_message', __('frontend.item.save-item-error-exist'));
                    \Session::flash('flash_type', 'danger');

                    return redirect()->route('page.item', $item->item_slug);
                }
                else
                {
                    $login_user->savedItems()->attach($item->id);

                    \Session::flash('flash_message', __('frontend.item.save-item-success'));
                    \Session::flash('flash_type', 'success');

                    return redirect()->route('page.item', $item->item_slug);
                }
            }
            else
            {
                \Session::flash('flash_message', __('frontend.item.save-item-error-login'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('page.item', $item->item_slug);
            }
        }
        else
        {
            abort(404);
        }
    }

    public function unSaveItem(Request $request, string $item_slug)
    {
        $item = Item::where('item_slug', $item_slug)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->first();

        if($item)
        {
            if(Auth::check())
            {
                $login_user = Auth::user();

                if($login_user->hasSavedItem($item->id))
                {
                    $login_user->savedItems()->detach($item->id);


                    \Session::flash('flash_message', __('frontend.item.unsave-item-success'));
                    \Session::flash('flash_type', 'success');

                    return redirect()->route('page.item', $item->item_slug);
                }
                else
                {
                    \Session::flash('flash_message', __('frontend.item.unsave-item-error-exist'));
                    \Session::flash('flash_type', 'danger');

                    return redirect()->route('page.item', $item->item_slug);
                }
            }
            else
            {
                \Session::flash('flash_message', __('frontend.item.unsave-item-error-login'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('page.item', $item->item_slug);
            }
        }
        else
        {
            abort(404);
        }

    }

    public function blog()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.frontend.blog', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        /**
         * Start fetch ads blocks
         */
        $advertisement = new Advertisement();

        $ads_before_breadcrumb = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_BLOG_POSTS_PAGES,
            Advertisement::AD_POSITION_BEFORE_BREADCRUMB,
            Advertisement::AD_STATUS_ENABLE
        );

        $ads_after_breadcrumb = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_BLOG_POSTS_PAGES,
            Advertisement::AD_POSITION_AFTER_BREADCRUMB,
            Advertisement::AD_STATUS_ENABLE
        );

        $ads_before_content = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_BLOG_POSTS_PAGES,
            Advertisement::AD_POSITION_BEFORE_CONTENT,
            Advertisement::AD_STATUS_ENABLE
        );

        $ads_after_content = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_BLOG_POSTS_PAGES,
            Advertisement::AD_POSITION_AFTER_CONTENT,
            Advertisement::AD_STATUS_ENABLE
        );

        $ads_before_sidebar_content = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_BLOG_POSTS_PAGES,
            Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT,
            Advertisement::AD_STATUS_ENABLE
        );

        $ads_after_sidebar_content = $advertisement->fetchAdvertisements(
            Advertisement::AD_PLACE_BLOG_POSTS_PAGES,
            Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT,
            Advertisement::AD_STATUS_ENABLE
        );
        /**
         * End fetch ads blocks
         */

        $data = [
            'posts' => \Canvas\Post::published()->orderByDesc('published_at')->paginate(10),
        ];

        $all_topics = \Canvas\Topic::orderBy('name')->get();
        $all_tags = \Canvas\Tag::orderBy('name')->get();

        $recent_posts = \Canvas\Post::published()->orderByDesc('published_at')->take(5)->get();

        /**
         * Start inner page header customization
         */
        $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
        /**
         * End inner page header customization
         */

        /**
         * Start initial blade view file path
         */
        $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
        $theme_view_path = $theme_view_path->getViewPath();
        /**
         * End initial blade view file path
         */

        return response()->view($theme_view_path . 'blog.index',
            compact('data', 'all_topics', 'all_tags', 'recent_posts',
                    'ads_before_breadcrumb', 'ads_after_breadcrumb', 'ads_before_content', 'ads_after_content',
                    'ads_before_sidebar_content', 'ads_after_sidebar_content', 'site_innerpage_header_background_type',
                    'site_innerpage_header_background_color', 'site_innerpage_header_background_image',
                    'site_innerpage_header_background_youtube_video', 'site_innerpage_header_title_font_color',
                    'site_innerpage_header_paragraph_font_color'));
    }

    public function blogByTag(string $tag_slug)
    {
        $tag = \Canvas\Tag::where('slug', $tag_slug)->first();

        if ($tag) {

            $settings = app('site_global_settings');

            /**
             * Start SEO
             */
            SEOMeta::setTitle(__('seo.frontend.blog-tag', ['tag_name' => $tag->name, 'site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
            SEOMeta::setDescription('');
            SEOMeta::setCanonical(URL::current());
            SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
            /**
             * End SEO
             */

            /**
             * Start fetch ads blocks
             */
            $advertisement = new Advertisement();

            $ads_before_breadcrumb = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_BLOG_TAG_PAGES,
                Advertisement::AD_POSITION_BEFORE_BREADCRUMB,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_breadcrumb = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_BLOG_TAG_PAGES,
                Advertisement::AD_POSITION_AFTER_BREADCRUMB,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_BLOG_TAG_PAGES,
                Advertisement::AD_POSITION_BEFORE_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_BLOG_TAG_PAGES,
                Advertisement::AD_POSITION_AFTER_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_sidebar_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_BLOG_TAG_PAGES,
                Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_sidebar_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_BLOG_TAG_PAGES,
                Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );
            /**
             * End fetch ads blocks
             */

            $data = [
                'posts' => \Canvas\Post::whereHas('tags', function ($query) use ($tag_slug) {
                    $query->where('slug', $tag_slug);
                })->published()->orderByDesc('published_at')->paginate(10),
            ];

            $all_topics = \Canvas\Topic::orderBy('name')->get();
            $all_tags = \Canvas\Tag::orderBy('name')->get();

            $recent_posts = \Canvas\Post::published()->orderByDesc('published_at')->take(5)->get();

            /**
             * Start inner page header customization
             */
            $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
            /**
             * End inner page header customization
             */

            /**
             * Start initial blade view file path
             */
            $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
            $theme_view_path = $theme_view_path->getViewPath();
            /**
             * End initial blade view file path
             */

            return response()->view($theme_view_path . 'blog.tag',
                compact('tag','data', 'all_topics', 'all_tags', 'recent_posts',
                    'ads_before_breadcrumb', 'ads_after_breadcrumb', 'ads_before_content', 'ads_after_content',
                    'ads_before_sidebar_content', 'ads_after_sidebar_content', 'site_innerpage_header_background_type',
                    'site_innerpage_header_background_color', 'site_innerpage_header_background_image',
                    'site_innerpage_header_background_youtube_video', 'site_innerpage_header_title_font_color',
                    'site_innerpage_header_paragraph_font_color'));

        } else {
            abort(404);
        }
    }

    public function blogByTopic(string $topic_slug)
    {
        $topic = \Canvas\Topic::where('slug', $topic_slug)->first();

        if ($topic) {

            $settings = app('site_global_settings');

            /**
             * Start SEO
             */
            SEOMeta::setTitle(__('seo.frontend.blog-topic', ['topic_name' => $topic->name, 'site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
            SEOMeta::setDescription('');
            SEOMeta::setCanonical(URL::current());
            SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
            /**
             * End SEO
             */

            /**
             * Start fetch ads blocks
             */
            $advertisement = new Advertisement();

            $ads_before_breadcrumb = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_BLOG_TOPIC_PAGES,
                Advertisement::AD_POSITION_BEFORE_BREADCRUMB,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_breadcrumb = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_BLOG_TOPIC_PAGES,
                Advertisement::AD_POSITION_AFTER_BREADCRUMB,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_BLOG_TOPIC_PAGES,
                Advertisement::AD_POSITION_BEFORE_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_BLOG_TOPIC_PAGES,
                Advertisement::AD_POSITION_AFTER_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_sidebar_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_BLOG_TOPIC_PAGES,
                Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_sidebar_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_BLOG_TOPIC_PAGES,
                Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );
            /**
             * End fetch ads blocks
             */

            $data = [
                'posts' => \Canvas\Post::whereHas('topic', function ($query) use ($topic_slug) {
                    $query->where('slug', $topic_slug);
                })->published()->orderByDesc('published_at')->paginate(10),
            ];

            $all_topics = \Canvas\Topic::orderBy('name')->get();
            $all_tags = \Canvas\Tag::orderBy('name')->get();

            $recent_posts = \Canvas\Post::published()->orderByDesc('published_at')->take(5)->get();

            /**
             * Start inner page header customization
             */
            $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
            /**
             * End inner page header customization
             */

            /**
             * Start initial blade view file path
             */
            $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
            $theme_view_path = $theme_view_path->getViewPath();
            /**
             * End initial blade view file path
             */

            return response()->view($theme_view_path . 'blog.topic',
                compact('topic', 'data', 'all_topics', 'all_tags', 'recent_posts',
                    'ads_before_breadcrumb', 'ads_after_breadcrumb', 'ads_before_content', 'ads_after_content',
                    'ads_before_sidebar_content', 'ads_after_sidebar_content', 'site_innerpage_header_background_type',
                    'site_innerpage_header_background_color', 'site_innerpage_header_background_image',
                    'site_innerpage_header_background_youtube_video', 'site_innerpage_header_title_font_color',
                    'site_innerpage_header_paragraph_font_color'));

        } else {
            abort(404);
        }
    }

    public function blogPost(string $blog_slug)
    {
        $posts = \Canvas\Post::with('tags', 'topic')->published()->get();
        $post = $posts->firstWhere('slug', $blog_slug);

        if (optional($post)->published) {

            $settings = app('site_global_settings');

            $post_meta = $post->meta;

            /**
             * Start SEO
             */
            SEOMeta::setTitle($post->title . ' - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
            SEOMeta::setDescription($post_meta['description']);
            SEOMeta::setCanonical(URL::current());
            SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
            /**
             * End SEO
             */

            /**
             * Start fetch ads blocks
             */
            $advertisement = new Advertisement();

            $ads_before_breadcrumb = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_SINGLE_POST_PAGE,
                Advertisement::AD_POSITION_BEFORE_BREADCRUMB,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_breadcrumb = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_SINGLE_POST_PAGE,
                Advertisement::AD_POSITION_AFTER_BREADCRUMB,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_feature_image = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_SINGLE_POST_PAGE,
                Advertisement::AD_POSITION_BEFORE_FEATURE_IMAGE,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_title = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_SINGLE_POST_PAGE,
                Advertisement::AD_POSITION_BEFORE_TITLE,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_post_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_SINGLE_POST_PAGE,
                Advertisement::AD_POSITION_BEFORE_POST_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_post_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_SINGLE_POST_PAGE,
                Advertisement::AD_POSITION_AFTER_POST_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_comments = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_SINGLE_POST_PAGE,
                Advertisement::AD_POSITION_BEFORE_COMMENTS,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_share = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_SINGLE_POST_PAGE,
                Advertisement::AD_POSITION_BEFORE_SHARE,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_share = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_SINGLE_POST_PAGE,
                Advertisement::AD_POSITION_AFTER_SHARE,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_before_sidebar_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_SINGLE_POST_PAGE,
                Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );

            $ads_after_sidebar_content = $advertisement->fetchAdvertisements(
                Advertisement::AD_PLACE_SINGLE_POST_PAGE,
                Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT,
                Advertisement::AD_STATUS_ENABLE
            );
            /**
             * End fetch ads blocks
             */

            $data = [
                'author' => $post->user,
                'post'   => $post,
                'meta'   => $post->meta,
            ];

            // IMPORTANT: This event must be called for tracking visitor/view traffic
            event(new \Canvas\Events\PostViewed($post));

            $all_topics = \Canvas\Topic::orderBy('name')->get();
            $all_tags = \Canvas\Tag::orderBy('name')->get();

            $recent_posts = \Canvas\Post::published()->orderByDesc('published_at')->take(5)->get();

            // used for comment
            $blog_post = BlogPost::published()->get()->firstWhere('slug', $blog_slug);

            /**
             * Start inner page header customization
             */
            $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
            /**
             * End inner page header customization
             */

            /**
             * Start initial blade view file path
             */
            $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
            $theme_view_path = $theme_view_path->getViewPath();
            /**
             * End initial blade view file path
             */

            return response()->view($theme_view_path . 'blog.show',
                compact('data', 'all_topics', 'all_tags', 'blog_post', 'recent_posts',
                        'ads_before_breadcrumb', 'ads_after_breadcrumb', 'ads_before_feature_image',
                        'ads_before_title', 'ads_before_post_content', 'ads_after_post_content',
                        'ads_before_comments', 'ads_before_share', 'ads_after_share', 'ads_before_sidebar_content',
                        'ads_after_sidebar_content', 'site_innerpage_header_background_type', 'site_innerpage_header_background_color',
                        'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
                        'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color'));
        } else {
            abort(404);
        }
    }

    public function jsonGetCitiesByState(int $state_id)
    {
        $state = State::findOrFail($state_id);
        $cities = $state->cities()->select('id', 'city_name')->orderBy('city_name')->get()->toJson();

        return response()->json($cities);
    }

    public function jsonGetStatesByCountry(int $country_id)
    {
        $country = Country::findOrFail($country_id);
        $states = $country->states()->select('id', 'state_name')->orderBy('state_name')->get()->toJson();

        return response()->json($states);
    }

    public function jsonDeleteSettingLogoImage()
    {
        Gate::authorize('delete-setting-image-logo');

        $settings = app('site_global_settings');

        $settings->deleteLogoImage();

        return response()->json(['success' => 'setting logo image deleted.']);
    }

    public function jsonDeleteSettingFaviconImage()
    {
        Gate::authorize('delete-setting-image-favicon');

        $settings = app('site_global_settings');

        $settings->deleteFaviconImage();

        return response()->json(['success' => 'setting favicon image deleted.']);
    }

    public function jsonDeleteUserProfileImage(int $user_id)
    {
        $gate_user = User::findOrFail($user_id);

        Gate::authorize('delete-user-image-profile', $gate_user);

        $gate_user->deleteProfileImage();

        return response()->json(['success' => 'user profile image deleted.']);
    }

    public function jsonDeleteItemFeatureImage(int $item_id)
    {
        $item = Item::findOrFail($item_id);

        Gate::authorize('delete-item-image-feature', $item);

        $item->deleteItemFeatureImage();

        return response()->json(['success' => 'item feature image deleted.']);
    }

    public function jsonDeleteCategoryImage(int $category_id)
    {
        Gate::authorize('delete-category-image');

        $category = Category::findOrFail($category_id);

        $category->deleteCategoryImage();

        return response()->json(['success' => 'category image deleted.']);
    }

    public function jsonDeleteProductFeatureImage(int $product_id)
    {
        $product = Product::findOrFail($product_id);

        Gate::authorize('delete-product-image-feature', $product);

        $product->deleteProductFeatureImage();

        return response()->json(['success' => 'product feature image deleted.']);
    }

    public function jsonDeleteItemImageGallery(int $item_image_gallery_id)
    {
        $item_image_gallery = ItemImageGallery::findOrFail($item_image_gallery_id);

        Gate::authorize('delete-item-image-gallery', $item_image_gallery);

        if(Storage::disk('public')->exists('item/gallery/' . $item_image_gallery->item_image_gallery_name)){
            Storage::disk('public')->delete('item/gallery/' . $item_image_gallery->item_image_gallery_name);
        }

        if(!empty($item_image_gallery->item_image_gallery_thumb_name) && Storage::disk('public')->exists('item/gallery/' . $item_image_gallery->item_image_gallery_thumb_name)){
            Storage::disk('public')->delete('item/gallery/' . $item_image_gallery->item_image_gallery_thumb_name);
        }

        $item_image_gallery->delete();

        return response()->json(['success' => 'item image gallery deleted.']);
    }

    public function jsonDeleteReviewImageGallery(int $review_image_gallery_id)
    {
        if(!Auth::check())
        {
            return response()->json(['error' => 'user not login']);
        }

        $review_image_gallery = DB::table('review_image_galleries')
            ->where('id', $review_image_gallery_id)
            ->get();

        if($review_image_gallery->count() == 0)
        {
            return response()->json(['error' => 'review image gallery not found.']);
        }

        $review_image_gallery = $review_image_gallery->first();

        $review_id = $review_image_gallery->review_id;

        $review = DB::table('reviews')
            ->where('id', $review_id)
            ->get();

        if($review->count() == 0)
        {
            return response()->json(['error' => 'review not found.']);
        }

        $review = $review->first();

        if(Auth::user()->id != $review->author_id)
        {
            return response()->json(['error' => 'you cannot delete review image gallery which does not belong to you.']);
        }

        if(Storage::disk('public')->exists('item/review/' . $review_image_gallery->review_image_gallery_name)){
            Storage::disk('public')->delete('item/review/' . $review_image_gallery->review_image_gallery_name);
        }

        if(Storage::disk('public')->exists('item/review/' . $review_image_gallery->review_image_gallery_thumb_name)){
            Storage::disk('public')->delete('item/review/' . $review_image_gallery->review_image_gallery_thumb_name);
        }

        DB::table('review_image_galleries')
            ->where('id', $review_image_gallery_id)
            ->delete();

        return response()->json(['success' => 'review image gallery deleted.']);
    }

    public function ajaxLocationSave(string $lat, string $lng)
    {
        $settings = app('site_global_settings');

        $old_lat = session('user_device_location_lat', 0.0);
        $old_lng = session('user_device_location_lng', 0.0);

        $reload_page = false;
        if($settings->setting_site_map_reload_homepage == Setting::SITE_MAP_RELOAD_HOMEPAGE_YES)
        {
            if(!empty($lat) && !empty($lng))
            {
                $distance_meters = get_vincenty_great_circle_distance($old_lat, $old_lng, $lat, $lng);

                // if the location changed more than 50000m, ask reload page
                if($distance_meters > 50000)
                {
                    $reload_page = true;
                }
            }
        }

        session(['user_device_location_lat' => $lat]);
        session(['user_device_location_lng' => $lng]);

        return response()->json([
            'message' => 'location lat & lng updated to session',
            'old_lat' => $old_lat,
            'old_lng' => $old_lng,
            'lat' => session('user_device_location_lat', ''),
            'lng' => session('user_device_location_lng', ''),
            'reload' => $reload_page,
            ]);
    }

    public function pricing(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('theme_directory_hub.pricing.seo.pricing', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $site_name = empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name;

        $login_user = null;

        if(Auth::check())
        {
            $login_user = Auth::user();
        }

        $plans = Plan::where('plan_status', Plan::PLAN_ENABLED)
            ->whereIn('plan_type', [Plan::PLAN_TYPE_FREE, Plan::PLAN_TYPE_PAID])
            ->orderBy('plan_type')
            ->orderBy('plan_period')
            ->get();

        /**
         * Start inner page header customization
         */
        $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
        /**
         * End inner page header customization
         */

        /**
         * Start initial blade view file path
         */
        $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
        $theme_view_path = $theme_view_path->getViewPath();
        /**
         * End initial blade view file path
         */

        return response()->view($theme_view_path . 'pricing',
            compact('plans','login_user', 'site_name', 'site_innerpage_header_background_type', 'site_innerpage_header_background_color',
                'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
                'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color'));
    }

    public function termsOfService(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.frontend.terms-service', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        if($settings->setting_page_terms_of_service_enable == Setting::TERM_PAGE_ENABLED)
        {
            $terms_of_service = $settings->setting_page_terms_of_service;

            /**
             * Start inner page header customization
             */
            $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
            /**
             * End inner page header customization
             */

            /**
             * Start initial blade view file path
             */
            $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
            $theme_view_path = $theme_view_path->getViewPath();
            /**
             * End initial blade view file path
             */

            return response()->view($theme_view_path . 'terms-of-service',
                compact('terms_of_service', 'site_innerpage_header_background_type', 'site_innerpage_header_background_color',
                        'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
                        'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color'));
        }
        else
        {
            return redirect()->route('page.home');
        }
    }

    public function privacyPolicy(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.frontend.privacy-policy', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        if($settings->setting_page_privacy_policy_enable == Setting::PRIVACY_PAGE_ENABLED)
        {
            $privacy_policy = $settings->setting_page_privacy_policy;

            /**
             * Start inner page header customization
             */
            $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

            $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
                ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
            /**
             * End inner page header customization
             */

            /**
             * Start initial blade view file path
             */
            $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
            $theme_view_path = $theme_view_path->getViewPath();
            /**
             * End initial blade view file path
             */

            return response()->view($theme_view_path . 'privacy-policy',
                compact('privacy_policy', 'site_innerpage_header_background_type', 'site_innerpage_header_background_color',
                        'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
                        'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color'));
        }
        else
        {
            return redirect()->route('page.home');
        }
    }

    /**
     * Update site language by the request of website footer language selector
     * @param Request $request
     * @param string $user_prefer_language
     * @return RedirectResponse
     */
    public function updateLocale(Request $request, string $user_prefer_language)
    {
        if(Auth::check())
        {
            $login_user = Auth::user();
            $login_user->user_prefer_language = $user_prefer_language;
            $login_user->save();
        }
        else
        {
            // save to language preference to session.
            Session::put('user_prefer_language', $user_prefer_language);
        }

        return redirect()->back();
    }


    /**
     * Update site country by the request of website footer country selector
     * @param Request $request
     * @param int $user_prefer_country_id
     * @return RedirectResponse
     */
    public function updateCountry(Request $request, int $user_prefer_country_id)
    {
        $country_exist = Country::find($user_prefer_country_id);
        if($country_exist)
        {
            if(Auth::check())
            {
                $login_user = Auth::user();
                $login_user->user_prefer_country_id = $country_exist->id;
                $login_user->save();
            }
            else
            {
                // save to language preference to session.
                Session::put('user_prefer_country_id', $country_exist->id);
            }
        }

        return redirect()->back();
    }

    /**
     * @param int $product_image_gallery_id
     * @return JsonResponse
     */
    public function jsonDeleteProductImageGallery(int $product_image_gallery_id)
    {
        $product_image_gallery = ProductImageGallery::findOrFail($product_image_gallery_id);

        Gate::authorize('delete-product-image-gallery', $product_image_gallery);

        if(Storage::disk('public')->exists('product/gallery/' . $product_image_gallery->product_image_gallery_name)){
            Storage::disk('public')->delete('product/gallery/' . $product_image_gallery->product_image_gallery_name);
        }

        if(Storage::disk('public')->exists('product/gallery/' . $product_image_gallery->product_image_gallery_thumb_name)){
            Storage::disk('public')->delete('product/gallery/' . $product_image_gallery->product_image_gallery_thumb_name);
        }

        $product_image_gallery->delete();

        return response()->json(['success' => 'product image gallery deleted.']);
    }

    private function getLatitude()
    {
        if(!empty(session('user_device_location_lat', '')))
        {
            $latitude = session('user_device_location_lat', '');
        }
        else
        {
            $latitude = app('site_global_settings')->setting_site_location_lat;
        }

        return $latitude;
    }

    private function getLongitude()
    {
        if(!empty(session('user_device_location_lng', '')))
        {
            $longitude = session('user_device_location_lng', '');
        }
        else
        {
            $longitude = app('site_global_settings')->setting_site_location_lng;
        }

        return $longitude;
    }

}

<?php

namespace App\Http\Controllers;

use App\Category;
use App\City;
use App\Item;
use App\Setting;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SitemapController extends Controller
{
    public function index(Request $request)
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_sitemap_index_enable == Setting::SITE_SITEMAP_INDEX_DISABLE)
        {
            abort(404);
        }
        else
        {
            // create new sitemap object
            $sitemap_index = App::make('sitemap');

            if($settings->setting_site_sitemap_page_include_to_index == Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
            {
                $sitemap_index->addSitemap(route('page.sitemap.page'));
            }

            if($settings->setting_site_sitemap_category_include_to_index == Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
            {
                $sitemap_index->addSitemap(route('page.sitemap.category'));
            }

            if($settings->setting_site_sitemap_listing_include_to_index == Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
            {
                $sitemap_index->addSitemap(route('page.sitemap.listing'));
            }

            if($settings->setting_site_sitemap_post_include_to_index == Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
            {
                $sitemap_index->addSitemap(route('page.sitemap.post'));
            }

            if($settings->setting_site_sitemap_tag_include_to_index == Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
            {
                $sitemap_index->addSitemap(route('page.sitemap.tag'));
            }

            if($settings->setting_site_sitemap_topic_include_to_index == Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
            {
                $sitemap_index->addSitemap(route('page.sitemap.topic'));
            }

            if($settings->setting_site_sitemap_state_include_to_index == Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
            {
                $sitemap_index->addSitemap(route('page.sitemap.state'));
            }

            if($settings->setting_site_sitemap_city_include_to_index == Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
            {
                $sitemap_index->addSitemap(route('page.sitemap.city'));
            }

            return $sitemap_index->render('sitemapindex');
        }
    }

    public function page(Request $request)
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_sitemap_page_enable == Setting::SITE_SITEMAP_PAGE_DISABLE)
        {
            abort(404);
        }
        else
        {
            $sitemap_page = App::make('sitemap');

            // start include website pages
            $sitemap_page->add(route('page.home'), $settings->updated_at, '1', $settings->setting_site_sitemap_page_frequency);
            $sitemap_page->add(route('page.search'), $settings->updated_at, '1', $settings->setting_site_sitemap_page_frequency);

            if($settings->setting_page_about_enable == Setting::ABOUT_PAGE_ENABLED)
            {
                $sitemap_page->add(route('page.about'), $settings->updated_at, '1', $settings->setting_site_sitemap_page_frequency);
            }
            if($settings->setting_page_terms_of_service_enable == Setting::TERM_PAGE_ENABLED)
            {
                $sitemap_page->add(route('page.terms-of-service'), $settings->updated_at, '1', $settings->setting_site_sitemap_page_frequency);
            }
            if($settings->setting_page_privacy_policy_enable == Setting::PRIVACY_PAGE_ENABLED)
            {
                $sitemap_page->add(route('page.privacy-policy'), $settings->updated_at, '1', $settings->setting_site_sitemap_page_frequency);
            }

            $sitemap_page->add(route('page.contact'), $settings->updated_at, '1', $settings->setting_site_sitemap_page_frequency);

            $sitemap_page->add(route('page.pricing'), $settings->updated_at, '1', $settings->setting_site_sitemap_page_frequency);
            // end include website pages

            // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
            return $sitemap_page->render($settings->setting_site_sitemap_page_format);
        }
    }

    public function category(Request $request)
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_sitemap_category_enable == Setting::SITE_SITEMAP_CATEGORY_DISABLE)
        {
            abort(404);
        }
        else
        {
            $sitemap_category = App::make('sitemap');

            $sitemap_category->add(route('page.categories'), $settings->updated_at, '1', $settings->setting_site_sitemap_category_frequency);

            $categories = Category::all();

            foreach($categories as $categories_key => $category)
            {
                $sitemap_category->add(route('page.category', ['category_slug' => $category->category_slug]), $category->updated_at, '1', $settings->setting_site_sitemap_category_frequency);
            }

            return $sitemap_category->render($settings->setting_site_sitemap_category_format);
        }
    }

    public function listing(Request $request)
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_sitemap_listing_enable == Setting::SITE_SITEMAP_LISTING_DISABLE)
        {
            abort(404);
        }
        else
        {
            $sitemap_listing_index = App::make('sitemap');

            $listings_count = Item::where('item_status', Item::ITEM_PUBLISHED)->count();

            if($listings_count % Setting::SITE_SITEMAP_MAXIMUM_URLS == 0)
            {
                $sitemap_listing_pages_count = intval($listings_count/Setting::SITE_SITEMAP_MAXIMUM_URLS);
            }
            else
            {
                $sitemap_listing_pages_count = intval($listings_count/Setting::SITE_SITEMAP_MAXIMUM_URLS) + 1;
            }

            for($i=1; $i<=$sitemap_listing_pages_count; $i++)
            {
                $sitemap_listing_index->addSitemap(route('page.sitemap.listing.pagination', ['page_number' => $i]));
            }

            return $sitemap_listing_index->render('sitemapindex');
        }
    }

    public function listingPagination(Request $request, int $page_number)
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_sitemap_listing_enable == Setting::SITE_SITEMAP_LISTING_DISABLE)
        {
            abort(404);
        }
        else
        {
            $sitemap_listing = App::make('sitemap');

            $listings_count = Item::where('item_status', Item::ITEM_PUBLISHED)->count();
            $listings_skip = ($page_number - 1) * Setting::SITE_SITEMAP_MAXIMUM_URLS;
            $listings_left = $listings_count - $listings_skip;
            if($listings_left <= Setting::SITE_SITEMAP_MAXIMUM_URLS)
            {
                $listings_take = $listings_left;
            }
            else
            {
                $listings_take = Setting::SITE_SITEMAP_MAXIMUM_URLS;
            }

            $items = Item::where('item_status', Item::ITEM_PUBLISHED)
                ->orderBy('updated_at', 'DESC')
                ->skip($listings_skip)
                ->take($listings_take)
                ->get();

            foreach($items as $items_key => $item)
            {
                $sitemap_listing->add(route('page.item', ['item_slug' => $item->item_slug]), $item->updated_at, '1', $settings->setting_site_sitemap_listing_frequency);
            }

            return $sitemap_listing->render($settings->setting_site_sitemap_listing_format);
        }
    }

    public function post(Request $request)
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_sitemap_post_enable == Setting::SITE_SITEMAP_POST_DISABLE)
        {
            abort(404);
        }
        else
        {
            $sitemap_post = App::make('sitemap');

            $posts = \Canvas\Post::published()->orderByDesc('published_at')->get();

            foreach($posts as $key => $post)
            {
                $sitemap_post->add(route('page.blog.show', ['blog_slug' => $post->slug]), $post->updated_at, '1', $settings->setting_site_sitemap_post_frequency);
            }

            return $sitemap_post->render($settings->setting_site_sitemap_post_format);
        }
    }

    public function tag(Request $request)
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_sitemap_tag_enable == Setting::SITE_SITEMAP_TAG_DISABLE)
        {
            abort(404);
        }
        else
        {
            $sitemap_tag = App::make('sitemap');

            $tags = \Canvas\Tag::orderBy('name')->get();

            foreach($tags as $key => $tag)
            {
                $sitemap_tag->add(route('page.blog.tag', ['tag_slug' => $tag->slug]), $tag->updated_at, '1', $settings->setting_site_sitemap_tag_frequency);
            }

            return $sitemap_tag->render($settings->setting_site_sitemap_tag_format);
        }
    }

    public function topic(Request $request)
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_sitemap_topic_enable == Setting::SITE_SITEMAP_TOPIC_DISABLE)
        {
            abort(404);
        }
        else
        {
            $sitemap_topic = App::make('sitemap');

            $topics = \Canvas\Topic::orderBy('name')->get();

            foreach($topics as $key => $topic)
            {
                $sitemap_topic->add(route('page.blog.topic', ['topic_slug' => $topic->slug]), $topic->updated_at, '1', $settings->setting_site_sitemap_topic_frequency);
            }

            return $sitemap_topic->render($settings->setting_site_sitemap_topic_format);
        }
    }

    public function state(Request $request)
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_sitemap_state_enable == Setting::SITE_SITEMAP_STATE_DISABLE)
        {
            abort(404);
        }
        else
        {
            $sitemap_state_index = App::make('sitemap');

            $states_count = State::count();

            if($states_count % Setting::SITE_SITEMAP_MAXIMUM_URLS == 0)
            {
                $sitemap_state_pages_count = intval($states_count/Setting::SITE_SITEMAP_MAXIMUM_URLS);
            }
            else
            {
                $sitemap_state_pages_count = intval($states_count/Setting::SITE_SITEMAP_MAXIMUM_URLS) + 1;
            }

            for($i=1; $i<=$sitemap_state_pages_count; $i++)
            {
                $sitemap_state_index->addSitemap(route('page.sitemap.state.pagination', ['page_number' => $i]));
            }

            return $sitemap_state_index->render('sitemapindex');
        }
    }

    public function statePagination(Request $request, int $page_number)
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_sitemap_state_enable == Setting::SITE_SITEMAP_STATE_DISABLE)
        {
            abort(404);
        }
        else
        {
            $sitemap_state = App::make('sitemap');

            $states_count = State::count();
            $states_skip = ($page_number - 1) * Setting::SITE_SITEMAP_MAXIMUM_URLS;
            $states_left = $states_count - $states_skip;
            if($states_left <= Setting::SITE_SITEMAP_MAXIMUM_URLS)
            {
                $states_take = $states_left;
            }
            else
            {
                $states_take = Setting::SITE_SITEMAP_MAXIMUM_URLS;
            }

            $states = State::orderBy('state_name')->skip($states_skip)->take($states_take)->get();

            $state_id_updated_at_array = array();
            foreach($states as $states_key => $state)
            {
                $state_updated_at = '';
                if(array_key_exists($state->id, $state_id_updated_at_array))
                {
                    $state_updated_at = $state_id_updated_at_array[$state->id];
                }
                else
                {
                    $latest_item_by_state_exist = Item::where('state_id', $state->id)
                        ->where('item_status', Item::ITEM_PUBLISHED)
                        ->count();

                    if($latest_item_by_state_exist > 0)
                    {
                        $state_updated_at = Item::where('state_id', $state->id)
                            ->where('item_status', Item::ITEM_PUBLISHED)
                            ->orderBy('updated_at', 'DESC')
                            ->first()->updated_at;

                        $state_id_updated_at_array[$state->id] = $state_updated_at;
                    }
                    else
                    {
                        $state_updated_at = $settings->updated_at;
                        $state_id_updated_at_array[$state->id] = $state_updated_at;
                    }
                }

                $sitemap_state->add(route('page.state', ['state_slug' => $state->state_slug]), $state_updated_at, '1', $settings->setting_site_sitemap_state_frequency);
            }

            return $sitemap_state->render($settings->setting_site_sitemap_state_format);
        }
    }

    public function city(Request $request)
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_sitemap_city_enable == Setting::SITE_SITEMAP_CITY_DISABLE)
        {
            abort(404);
        }
        else
        {
            $sitemap_city_index = App::make('sitemap');

            $cities_count = City::count();

            if($cities_count % Setting::SITE_SITEMAP_MAXIMUM_URLS == 0)
            {
                $sitemap_city_pages_count = intval($cities_count/Setting::SITE_SITEMAP_MAXIMUM_URLS);
            }
            else
            {
                $sitemap_city_pages_count = intval($cities_count/Setting::SITE_SITEMAP_MAXIMUM_URLS) + 1;
            }

            for($i=1; $i<=$sitemap_city_pages_count; $i++)
            {
                $sitemap_city_index->addSitemap(route('page.sitemap.city.pagination', ['page_number' => $i]));
            }

            return $sitemap_city_index->render('sitemapindex');
        }
    }

    public function cityPagination(Request $request, int $page_number)
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_sitemap_city_enable == Setting::SITE_SITEMAP_CITY_DISABLE)
        {
            abort(404);
        }
        else
        {
            $sitemap_city = App::make('sitemap');

            $cities_count = City::count();
            $cities_skip = ($page_number - 1) * Setting::SITE_SITEMAP_MAXIMUM_URLS;
            $cities_left = $cities_count - $cities_skip;
            if($cities_left <= Setting::SITE_SITEMAP_MAXIMUM_URLS)
            {
                $cities_take = $cities_left;
            }
            else
            {
                $cities_take = Setting::SITE_SITEMAP_MAXIMUM_URLS;
            }

            $cities = City::orderBy('state_id')->skip($cities_skip)->take($cities_take)->get();

            $state_id_slug_array = array();
            $state_id_updated_at_array = array();

            foreach($cities as $cities_key => $city)
            {
                $state_slug = '';
                if(array_key_exists($city->state_id, $state_id_slug_array))
                {
                    $state_slug = $state_id_slug_array[$city->state_id];
                }
                else
                {
                    $state_by_city = $city->state()->first();

                    if($state_by_city)
                    {
                        $state_slug = $state_by_city->state_slug;
                        $state_id_slug_array[$city->state_id] = $state_slug;
                    }
                }

                // check if $state_slug empty, continue to next loop if empty
                if(empty($state_slug))
                {
                    continue;
                }

                $city_updated_at = '';
                if(array_key_exists($city->state_id, $state_id_updated_at_array))
                {
                    $city_updated_at = $state_id_updated_at_array[$city->state_id];
                }
                else
                {
                    $latest_item_by_state_exist = Item::where('state_id', $city->state_id)
                        ->where('item_status', Item::ITEM_PUBLISHED)
                        ->count();

                    if($latest_item_by_state_exist > 0)
                    {
                        $city_updated_at = Item::where('state_id', $city->state_id)
                            ->where('item_status', Item::ITEM_PUBLISHED)
                            ->orderBy('updated_at', 'DESC')
                            ->first()->updated_at;

                        $state_id_updated_at_array[$city->state_id] = $city_updated_at;
                    }
                    else
                    {
                        $city_updated_at = $settings->updated_at;
                        $state_id_updated_at_array[$city->state_id] = $city_updated_at;
                    }
                }

                $sitemap_city->add(route('page.city', ['state_slug' => $state_slug, 'city_slug' => $city->city_slug]), $city_updated_at, '1', $settings->setting_site_sitemap_city_frequency);
            }

            return $sitemap_city->render($settings->setting_site_sitemap_city_format);
        }
    }
}

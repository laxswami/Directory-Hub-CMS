<?php

use App\CustomField;
use App\Item;
use App\ItemFeature;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class ItemSeeder
 *
 * This seeder is used for generating random listings date in items table.
 * The purpose of this seeder is to optimizing the script performance in mega
 * date set.
 *
 * php artisan db:seed --class=ItemSeeder
 */
class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $batch_run_planned = 100;
        $batch_run_processed = 0;

        $reviews = [
            [
                'rating' => 5,
                'customer_service_rating' => 5,
                'quality_rating' => 5,
                'friendly_rating' => 5,
                'pricing_rating' => 5,
                'title' => "Love this place",
                'body' => "Love this place. Amazing service, delicious dishes. Love the Poutine burger - an amazing take on a Québécois classic. The jerk wings are an amazing starter.",
            ],
            [
                'rating' => 5,
                'customer_service_rating' => 5,
                'quality_rating' => 5,
                'friendly_rating' => 5,
                'pricing_rating' => 5,
                'title' => "I like the atmosphere and locally owned",
                'body' => "First time here and it was really nice. I like the atmosphere and locally owned.  Service was excellent and I had the short ribs which were tender and delicious.",
            ],
            [
                'rating' => 5,
                'customer_service_rating' => 5,
                'quality_rating' => 5,
                'friendly_rating' => 5,
                'pricing_rating' => 5,
                'title' => 'Great happy hour',
                'body' => "Great happy hour: $5 really great wine and some tasty plates too. I had the pork tacos with citrus goat cheese--they were fantastic. Too big of a portion to finish myself, but there average person could. Arela F was my server and she was fantastic!! Probably the best service I've had in a long time--and I eat out too much haha. I'll definitely be back.",
            ],
            [
                'rating' => 5,
                'customer_service_rating' => 5,
                'quality_rating' => 5,
                'friendly_rating' => 5,
                'pricing_rating' => 5,
                'title' => "Highly recommended!",
                'body' => "While visiting family on the Front Range, Samples was recommended as a great place for a healthful and tasty lunch.  They were absolutely right!  Great food, and excellent service from Arela, definitely made Samples a memorable spot, one we will return to the next time we're in the area.  Highly recommended!",
            ],
            [
                'rating' => 4,
                'customer_service_rating' => 5,
                'quality_rating' => 4,
                'friendly_rating' => 5,
                'pricing_rating' => 3,
                'title' => "Expensive",
                'body' => "Not bad. Kinda expensive for what you get. Decent food.  Decent service. $80+ for lunch and 4 drinks. :)",
            ],
            [
                'rating' => 4,
                'customer_service_rating' => 3,
                'quality_rating' => 5,
                'friendly_rating' => 3,
                'pricing_rating' => 4,
                'title' => "I've never  disappointed",
                'body' => "I've been here on several occasions, and I've never  disappointed. I really like the ambience, and the menu has good selection. The Korean short ribs are the best option I've tried. We go back specifically for those. The other dishes we've tried were good, not always amazing, but tasty. The service really makes this place. The one time we had a problem with our order the server and the manager went out of their way to make it right. It's great to have a place like this to go in Longmont!",
            ],
            [
                'rating' => 3,
                'customer_service_rating' => 3,
                'quality_rating' => 4,
                'friendly_rating' => 4,
                'pricing_rating' => 2,
                'title' => "Never feels like a good value price-wise",
                'body' => "Really, really good-tasting food, but never feels like a good value price-wise.  Also, too many times here I've had bad/really slow service.  With small kids that can get dicey, but if you're sitting and drinking with friends, not such a big deal.  But still, drinks and food are on the pricier side for what you get.",
            ],
            [
                'rating' => 3,
                'customer_service_rating' => 2,
                'quality_rating' => 4,
                'friendly_rating' => 2,
                'pricing_rating' => 1,
                'title' => "Rude",
                'body' => "The place was good food-wise, but back in MARCH 2018, I was there for my 27th birthday celebration and only TWO EMPLOYEES had noticed, but didn't do anything. The manager on duty the night we went in was absolutely rude, never gave us the time of day. He didn't even offer the dessert menu so we could look over it. There were 6 of us in attendance. I dealt with a small dessert from my husband when we got home, since he knew I was not happy about our experience. I dont recommend celebrating your birthday here. Go somewhere else.",
            ],
            [
                'rating' => 2,
                'customer_service_rating' => 2,
                'quality_rating' => 4,
                'friendly_rating' => 3,
                'pricing_rating' => 2,
                'title' => "Quality seems to have gone down since last fall",
                'body' => "Several of the best recipes were taken off of the menu or changed, and the overall quality seems to have gone down since last fall. The overall vibe of the restaurant is very nice, but it seems as though the food is nowhere near as amazing as it used to be.",
            ],
            [
                'rating' => 1,
                'customer_service_rating' => 3,
                'quality_rating' => 5,
                'friendly_rating' => 4,
                'pricing_rating' => 1,
                'title' => "Too bad because I do like the idea of the place",
                'body' => "We came for the beer menu, and it was good. The problem is the mediocre food and the higher prices than comparable restaurants, at least by looks. This one is certainly not in the same league as most when it comes to food. Too bad because I do like the idea of the place.",
            ],
        ];

        $faker = \Faker\Factory::create();

        echo "Seeding\n";
        echo "Batch Planned: " . strval($batch_run_planned) . "\n";

        for($i=0;$i<$batch_run_planned;$i++)
        {
            $random_categories = DB::table('categories')
                ->inRandomOrder()
                ->take(20)
                ->pluck('id')
                ->toArray();

            $random_item_categories_string = '';
            foreach($random_categories as $random_categories_key => $random_category_id)
            {
                $random_category_find = \App\Category::find($random_category_id);

                if($random_category_find)
                {
                    $random_item_categories_string .= $random_category_find->category_name . ' ';
                }

            }

            $random_user = DB::table('users')
                ->where('email_verified_at', '!=', null)
                ->where('user_suspended', User::USER_NOT_SUSPENDED)
                ->inRandomOrder()->first();

            $random_country = DB::table('countries')->where('country_abbr', 'US')
                ->inRandomOrder()
                ->first();

            $random_state = null;
            $random_city = null;

            if($random_country)
            {
                $random_state = DB::table('states')->where('country_id', $random_country->id)
                    ->inRandomOrder()->first();
            }
            else
            {
                continue;
            }

            if($random_state)
            {
                $random_city = DB::table('cities')->where('state_id', $random_state->id)
                    ->inRandomOrder()->first();
            }
            else
            {
                continue;
            }

            if($random_city)
            {
                $random_item_lat = $random_city->city_lat;
                $random_item_lng = $random_city->city_lng;

                //$random_item_title = "Seeder " . strval(time());
                $random_item_title = $faker->company;

                $random_item_slug = str_slug($random_item_title . '-' . uniqid());
                $random_item_description = $random_item_title . " in " . $random_city->city_name . ", " . $random_state->state_name. ", " . $random_country->country_name . ".";

                $random_item_postal_code = substr($faker->postcode, 0, 5);
                $random_item_address = $faker->streetAddress;
                $random_item_address_hide = rand(0,1);

                $random_item_website = 'https://' . $faker->domainName;
                $random_item_phone = $faker->tollFreePhoneNumber;

                $random_item_social_facebook = 'https://facebook.com';
                $random_item_social_twitter = 'https://twitter.com';
                $random_item_social_linkedin = 'https://linkedin.com';

                $random_item_featured = rand(0,10) > 6 ? \App\Item::ITEM_FEATURED : \App\Item::ITEM_NOT_FEATURED;
                $random_item_featured_by_admin = $random_item_featured == \App\Item::ITEM_FEATURED ? \App\Item::ITEM_FEATURED_BY_ADMIN : \App\Item::ITEM_NOT_FEATURED_BY_ADMIN;

                // start create a new item model and save the new record to database
                $new_item = new Item(array(
                    'user_id' => $random_user->id,
                    'item_status' => Item::ITEM_PUBLISHED,
                    'item_featured' => $random_item_featured,
                    'item_featured_by_admin' => $random_item_featured_by_admin,
                    'item_title' => $random_item_title,
                    'item_slug' => $random_item_slug,
                    'item_description' => $random_item_description,
                    'item_address' => $random_item_address,
                    'item_address_hide' => $random_item_address_hide,
                    'city_id' => $random_city->id,
                    'state_id' => $random_state->id,
                    'country_id' => $random_country->id,
                    'item_postal_code' => $random_item_postal_code,
                    'item_lat' => $random_item_lat,
                    'item_lng' => $random_item_lng,
                    'item_phone' => $random_item_phone,
                    'item_website' => $random_item_website,
                    'item_social_facebook' => $random_item_social_facebook,
                    'item_social_twitter' => $random_item_social_twitter,
                    'item_social_linkedin' => $random_item_social_linkedin,
                    'item_type' => Item::ITEM_TYPE_REGULAR,
                    'item_location_str' => $random_city->city_name . ' ' . $random_state->state_name . ' ' . $random_country->country_name . ' ' . $random_item_postal_code,
                    'item_categories_string' => $random_item_categories_string,
                    'item_hour_time_zone' => 'America/New_York',
                    'item_hour_show_hours' => \App\Item::ITEM_HOUR_SHOW,
                ));
                $new_item->save();

                // sync the categories for the new item record
                $new_item->allCategories()->sync($random_categories);

                // start create the item features for this new item
                $category_custom_fields = new CustomField();
                $category_custom_fields = $category_custom_fields->getDistinctCustomFieldsByCategories($random_categories);

                if($category_custom_fields->count() > 0)
                {
                    $item_features_string = "";

                    foreach($category_custom_fields as $category_custom_fields_key => $custom_field)
                    {

                        $item_feature_value = "";

                        if($custom_field->custom_field_type == CustomField::TYPE_LINK)
                        {
                            $item_feature_value = 'https://' . $faker->domainName;
                        }
                        else
                        {
                            $item_feature_value = 'random feature ' . strval(time());
                        }

                        $new_item_feature = new ItemFeature(array(
                            'custom_field_id' => $custom_field->id,
                            'item_feature_value' => $item_feature_value,
                        ));

                        $created_item_feature = $new_item->features()->save($new_item_feature);

                        $item_features_string .= $item_feature_value . ' ';
                    }

                    $new_item->item_features_string = $item_features_string;
                    $new_item->save();
                }

                /**
                 * Start insert random reviews for this item
                 */
                $random_user_review = DB::table('users')
                    ->where('email_verified_at', '!=', null)
                    ->where('user_suspended', User::USER_NOT_SUSPENDED)
                    ->where('id', '!=', $random_user->id)
                    ->inRandomOrder()->first();

                $random_review_key = rand(0, count($reviews) - 1);

                DB::table('reviews')->insert([
                    [
                        'rating' => $reviews[$random_review_key]['rating'],
                        'customer_service_rating' => $reviews[$random_review_key]['customer_service_rating'],
                        'quality_rating' => $reviews[$random_review_key]['quality_rating'],
                        'friendly_rating' => $reviews[$random_review_key]['friendly_rating'],
                        'pricing_rating' => $reviews[$random_review_key]['pricing_rating'],
                        'recommend' => $reviews[$random_review_key]['rating'] > 3 ? \App\Item::ITEM_REVIEW_RECOMMEND_YES : \App\Item::ITEM_REVIEW_RECOMMEND_NO,
                        'department' => 'Sales',
                        'title' => $reviews[$random_review_key]['title'],
                        'body' => $reviews[$random_review_key]['body'],
                        'approved' => \App\Item::ITEM_REVIEW_APPROVED,
                        'reviewrateable_type' => "App\Item",
                        'reviewrateable_id' => $new_item->id,
                        'author_type' => "App\User",
                        'author_id' => $random_user_review->id,

                        'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                        'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                    ],
                ]);

                $new_item->syncItemAverageRating();
                /**
                 * End insert random reviews for this item
                 */

                /**
                 * Start insert hours
                 */
                $random_item_hour_open_time_hour = strval(rand(5,11));
                $random_item_hour_close_time_hour = strval(rand(18,23));

                $random_item_hour_open_time_minute = rand(1, 10) > 5 ? '30' : '00';
                $random_item_hour_close_time_minute = rand(1, 10) > 5 ? '30' : '00';

                $random_day_of_week_close_all_day = rand(1,7);

                for($day_of_week=1;$day_of_week<8;$day_of_week++)
                {
                    if($day_of_week == $random_day_of_week_close_all_day)
                    {
                        continue;
                    }

                    $item_hour_open_time = $random_item_hour_open_time_hour . ':'. $random_item_hour_open_time_minute .':00';
                    $item_hour_close_time = $random_item_hour_close_time_hour . ':' . $random_item_hour_close_time_minute . ':00';

                    DB::table('item_hours')->insert([
                        [
                            'item_id' => $new_item->id,
                            'item_hour_day_of_week' => $day_of_week,
                            'item_hour_open_time' => $item_hour_open_time,
                            'item_hour_close_time' => $item_hour_close_time,
                            'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                            'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                        ],
                    ]);
                }
                /**
                 * End insert hours
                 */

                /**
                 * Start insert hour exceptions
                 */
                DB::table('item_hour_exceptions')->insert([
                    [
                        'item_id' => $new_item->id,
                        'item_hour_exception_date' => date('Y') . '-12-25',
                        'item_hour_exception_open_time' => null,
                        'item_hour_exception_close_time' => null,
                        'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                        'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                    ],
                ]);
                /**
                 * End insert hour exceptions
                 */

            }
            else
            {
                continue;
            }

            $batch_run_processed += 1;

            echo "Processed: " . strval($batch_run_processed) . ". Batch Planned: " . strval($i) . "\n";
        }

        echo "Completed\n";
        echo "Batch Planned: " . strval($batch_run_planned) . "\n";
        echo "Batch Processed: " . strval($batch_run_processed) . "\n";
        echo "Item Total: " . strval(DB::table('items')->count()) . "\n";
    }
}

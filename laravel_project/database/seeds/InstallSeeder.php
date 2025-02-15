<?php

use App\Country;
use App\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class InstallSeeder extends Seeder
{
    const FREE_PLAN_ID = 1;
    const ADMIN_PLAN_ID = 2;
    const TOTAL_USERS = 10;
    const TOTAL_LISTING_USERS = 5;

    const REVIEW_MIN = 3;
    const REVIEW_MAX = 10;
    /**
     * Run the database seeds for installation process
     *
     * @return void
     */
    public function run()
    {
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

        /**
         * Pre-defined roles
         */
        DB::table('roles')->insert([
            [
                // role_id = 1
                'id'            => \App\Role::ADMIN_ROLE_ID,
                'name'          => 'Admin',
                'slug'          => 'admin',
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ],
            [
                // role_id = 3
                'id'            => \App\Role::USER_ROLE_ID,
                'name'          => 'User',
                'slug'          => 'user',
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);

        /**
         * Testimonials
         */
        DB::table('testimonials')->insert([
            [
                'testimonial_name' => "Liam Kaufman",
                'testimonial_company' => "Project Studio Solutions",
                'testimonial_job_title' => "Founder",
                'testimonial_description' => "WOW! This is great, I think this will be very soon the best directory solution of all, the developer is releasing updates very often and is open to suggestions, can't wait to see how is it going to evolve. Don't hesitate to buy it, you won't regret it.",
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);

        DB::table('testimonials')->insert([
            [
                'testimonial_name' => "Jeff Dawson",
                'testimonial_company' => "JD Web Publishing",
                'testimonial_job_title' => 'CEO',
                'testimonial_description' => "Amazing product for local listings with growing features, regular and frequent updates with passion. Always seeks client inputs, collaborative, extends support and clarification, very friendly. Great work!",
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);

        DB::table('testimonials')->insert([
            [
                'testimonial_name' => 'Bette Brennan',
                'testimonial_company' => 'Forayweb',
                'testimonial_job_title' => 'Tech Lead',
                'testimonial_description' => 'WOW! One of the Best Creative Software Programmers and Offers Great Support and Listens to suggestions. Great script idea\'s for Entrepreneurs.',
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);

        /**
         * Faqs
         */
        DB::table('faqs')->insert([
            [
                'faqs_question' => 'How to list my item?',
                'faqs_answer' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti esse voluptates deleniti, ratione, suscipit quam cumque beatae, enim mollitia voluptatum velit excepturi possimus odio dolore molestiae officiis aspernatur provident praesentium.',
                'faqs_order' => 1,
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);
        DB::table('faqs')->insert([
            [
                'faqs_question' => 'Is this available in my country?',
                'faqs_answer' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti esse voluptates deleniti, ratione, suscipit quam cumque beatae, enim mollitia voluptatum velit excepturi possimus odio dolore molestiae officiis aspernatur provident praesentium.',
                'faqs_order' => 2,
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);
        DB::table('faqs')->insert([
            [
                'faqs_question' => 'Is it free?',
                'faqs_answer' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti esse voluptates deleniti, ratione, suscipit quam cumque beatae, enim mollitia voluptatum velit excepturi possimus odio dolore molestiae officiis aspernatur provident praesentium.',
                'faqs_order' => 3,
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);
        DB::table('faqs')->insert([
            [
                'faqs_question' => 'How the system works?',
                'faqs_answer' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti esse voluptates deleniti, ratione, suscipit quam cumque beatae, enim mollitia voluptatum velit excepturi possimus odio dolore molestiae officiis aspernatur provident praesentium.',
                'faqs_order' => 4,
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);

        /**
         * Social Media
         */
        DB::table('social_medias')->insert([
            [
                'social_media_name' => 'Facebook',
                'social_media_icon' => 'fab fa-facebook-f',
                'social_media_link' => 'https://facebook.com',
                'social_media_order' => 1,
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);
        DB::table('social_medias')->insert([
            [
                'social_media_name' => 'Twitter',
                'social_media_icon' => 'fab fa-twitter',
                'social_media_link' => 'https://twitter.com',
                'social_media_order' => 2,
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);
        DB::table('social_medias')->insert([
            [
                'social_media_name' => 'Instagram',
                'social_media_icon' => 'fab fa-instagram',
                'social_media_link' => 'https://instagram.com',
                'social_media_order' => 3,
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);
        DB::table('social_medias')->insert([
            [
                'social_media_name' => 'LinkedIn',
                'social_media_icon' => 'fab fa-linkedin-in',
                'social_media_link' => 'https://linkedin.com',
                'social_media_order' => 4,
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);

        /**
         * Import location of United States
         */
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_1.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_2.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_3.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_4.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_5.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_6.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_7.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_8.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_9.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_10.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_11.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_12.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_13.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_14.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_15.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_16.sql'));
        DB::unprepared(File::get(__DIR__ . '/sql/cities_us_17.sql'));

        /**
         * Website settings
         */
        $default_frontend_theme = \App\Theme::where('theme_type', \App\Theme::THEME_TYPE_FRONTEND)
            ->where('theme_status', \App\Theme::THEME_STATUS_ACTIVE)
            ->where('theme_system_default', \App\Theme::THEME_SYSTEM_DEFAULT_YES)->first();

        DB::table('settings')->insert([
            [
                'id' => 1,
                'setting_site_name' => config('app.name', 'Directory Hub'),
                'setting_site_email' => 'email@example.com',
                'setting_site_phone' => '+1 232 3235 324',
                'setting_site_address' => '2345 Garyson Street',
                'setting_site_state' => 'New York',
                'setting_site_city' => 'White Plains',
                'setting_site_country' => 'United States', // United States
                'setting_site_postal_code' => '98765',
                'setting_site_about' => "Directory Hub is a business directory and listing CMS, inspired by Yelp, provides features like unlimited-level categories, custom fields, listing with multiple categories. It offers buyers the maximum extensibility to make any type of business or niche directory.",

                'setting_site_location_lat' => 31.89160000,
                'setting_site_location_lng' => -97.06710000,
                'setting_site_location_country_id' => 395,

                'setting_site_seo_home_title' => 'Restaurants, Dentists, Bars, Beauty Salons, Doctors',
                'setting_site_seo_home_description' => 'User Reviews and Recommendations of Best Restaurants, Shopping, Nightlife, Food, Entertainment, Things to Do, Services and More',
                'setting_site_seo_home_keywords' => 'recommendation,local,business,review,friend,restaurant,dentist,doctor,salon,spa,shopping,store,share,community,massage,sushi,pizza,nails',

                'setting_page_about_enable' => Setting::ABOUT_PAGE_ENABLED,
                'setting_page_about' => "<h2>Shaping the Future of Business</h2><p>We are committed to nurturing a neutral platform and are helping business establishments maintain high standards.<br></p><p><br></p><h2>Who Are We?</h2><p>Welcome to Listing Plus, your number one source for all things. We're dedicated to giving you the very best of business information.</p><p>Listing Plus has come a long way from its beginnings. When first started out, our passion for business growing drove them so that Listing Plus can now serve customers all over the world, and are thrilled that we're able to turn our passion into our own website.</p><p>We hope you enjoy our products as much as we enjoy offering them to you. If you have any questions or comments, please don't hesitate to contact us.</p><p><br></p><h2>Our Values</h2><p><br></p><h3>Resilience</h3><p>We push ourselves beyond our abilities when faced with tough times. When we foresee uncertainty, we address it only with flexibility.</p><p><br></p><h3>Acceptance</h3><p>Feedback is never taken personally, we break it into positive pieces and strive to work on each and every element even more effectively.</p><p><br></p><h3>Humility</h3><p>It’s always ‘us’ over ‘me’. We don’t lose ourselves in pride or confidence during individual successes but focus on being our simple selves in every which way.</p><p><br></p><h3>Spark</h3><p>We believe in, stand for, and are evangelists of our culture - both, within Zomato and externally with all our stakeholders.</p><p><br></p><h3>Judgment</h3><p>It’s not our abilities that show who we truly are - it’s our choices. We aim to get these rights, at least in the majority of the cases.</p>",

                'setting_page_terms_of_service_enable' => Setting::TERM_PAGE_ENABLED,
                'setting_page_terms_of_service' => "<p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\">Please read these terms of service (\"terms\", \"terms of service\") carefully before using [website] website (the \"service\") operated by [name] (\"us\", 'we\", \"our\").</p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\"><strong>Conditions of Use</strong></p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\">We will provide their services to you, which are subject to the conditions stated below in this document. Every time you visit this website, use its services or make a purchase, you accept the following conditions. This is why we urge you to read them carefully.</p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\"><strong>Privacy Policy</strong></p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\">Before you continue using our website we advise you to read our privacy policy [link to privacy policy] regarding our user data collection. It will help you better understand our practices.</p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\"><strong>Copyright</strong></p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\">Content published on this website (digital downloads, images, texts, graphics, logos) is the property of [name] and/or its content creators and protected by international copyright laws. The entire compilation of the content found on this website is the exclusive property of [name], with copyright authorship for this compilation by [name].</p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\"><strong>Communications</strong></p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\">The entire communication with us is electronic. Every time you send us an email or visit our website, you are going to be communicating with us. You hereby consent to receive communications from us. If you subscribe to the news on our website, you are going to receive regular emails from us. We will continue to communicate with you by posting news and notices on our website and by sending you emails. You also agree that all notices, disclosures, agreements and other communications we provide to you electronically meet the legal requirements that such communications be in writing.</p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\"><strong>Applicable Law</strong></p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\">By visiting this website, you agree that the laws of the [your location], without regard to principles of conflict laws, will govern these terms of service, or any dispute of any sort that might come between [name] and you, or its business partners and associates.</p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\"><strong style=\"font-family: Nunito, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 1rem;\">Disputes</strong><br></p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\">Any dispute related in any way to your visit to this website or to products you purchase from us shall be arbitrated by state or federal court [your location] and you consent to exclusive jurisdiction and venue of such courts.</p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\"><span style=\"font-family: Nunito, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 1rem;\"><strong>Comments, Reviews, and Emails</strong></span><br></p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\">Visitors may post content as long as it is not obscene, illegal, defamatory, threatening, infringing of intellectual property rights, invasive of privacy or injurious in any other way to third parties. Content has to be free of software viruses, political campaign, and commercial solicitation.</p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\">We reserve all rights (but not the obligation) to remove and/or edit such content. When you post your content, you grant [name] non-exclusive, royalty-free and irrevocable right to use, reproduce, publish, modify such content throughout the world in any media.</p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\"><strong>License and Site Access</strong></p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\">We grant you a limited license to access and make personal use of this website. You are not allowed to download or modify it. This may be done only with written consent from us.</p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\"><strong>User Account</strong></p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\">If you are an owner of an account on this website, you are solely responsible for maintaining the confidentiality of your private user details (username and password). You are responsible for all activities that occur under your account or password.</p><p style=\"border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;\">We reserve all rights to terminate accounts, edit or remove content and cancel orders in their sole discretion.</p>",

                'setting_page_privacy_policy_enable' => Setting::PRIVACY_PAGE_ENABLED,
                'setting_page_privacy_policy' => "<p>This privacy policy (\"policy\") will help you understand how [name] (\"us\", \"we\", \"our\") uses and protects the data you provide to us when you visit and use [website] (\"website\", \"service\").</p><p>We reserve the right to change this policy at any given time, of which you will be promptly updated. If you want to make sure that you are up to date with the latest changes, we advise you to frequently visit this page.</p><p><strong>What User Data We Collect</strong></p><p>When you visit the website, we may collect the following data:</p><p><ul><li>Your IP address.</li><li>Your contact information and email address.</li><li>Other information such as interests and preferences.</li><li>Data profile regarding your online behavior on our website.</li></ul></p><p><strong>Why We Collect Your Data</strong></p><p>We are collecting your data for several reasons:</p><p><ul><li>To better understand your needs.</li><li>To improve our services and products.</li><li>To send you promotional emails containing the information we think you will find interesting.</li><li>To contact you to fill out surveys and participate in other types of market research.</li><li>To customize our website according to your online behavior and personal preferences.</li></ul></p><p><strong>Safeguarding and Securing the Data</strong></p><p>[name] is committed to securing your data and keeping it confidential. [name] has done all in its power to prevent data theft, unauthorized access, and disclosure by implementing the latest technologies and software, which help us safeguard all the information we collect online.</p><p><strong>Our Cookie Policy</strong></p><p>Once you agree to allow our website to use cookies, you also agree to use the data it collects regarding your online behavior (analyze web traffic, web pages you spend the most time on, and websites you visit).</p><p>The data we collect by using cookies is used to customize our website to your needs. After we use the data for statistical analysis, the data is completely removed from our systems.</p><p>Please note that cookies don't allow us to gain control of your computer in any way. They are strictly used to monitor which pages you find useful and which you do not so that we can provide a better experience for you.</p><p>If you want to disable cookies, you can do it by accessing the settings of your internet browser. (Provide links for cookie settings for major internet browsers).</p><p><span style=\"font-family: Nunito, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 1rem;\"><strong>Links to Other Websites</strong></span></p><p>Our website contains links that lead to other websites. If you click on these links [name] is not held responsible for your data and privacy protection. Visiting those websites is not governed by this privacy policy agreement. Make sure to read the privacy policy documentation of the website you go to from our website.</p><p><strong>Restricting the Collection of your Personal Data</strong></p><p>At some point, you might wish to restrict the use and collection of your personal data. You can achieve this by doing the following:</p><p><ul><li>When you are filling the forms on the website, make sure to check if there is a box which you can leave unchecked, if you don't want to disclose your personal information.</li><li>If you have already agreed to share your information with us, feel free to contact us via email and we will be more than happy to change this for you.</li></ul></p><p>[name] will not lease, sell or distribute your personal information to any third parties, unless we have your permission. We might do so if the law forces us. Your personal information will be used when we need to send you promotional materials if you agree to this privacy policy.</p>",

                'setting_site_active_theme_id' => $default_frontend_theme->id,

                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);

        $setting_license = \App\SettingLicense::where('setting_id', 1)->first();
        $setting_license->settings_license_purchase_code = env('CODECANYON_PURCHASE_CODE', '');
        $setting_license->settings_license_codecanyon_username = env('CODECANYON_USERNAME', '');
        $setting_license->save();

        /**
         * Insert pre-defined plans
         */
        $plan_id = DB::table('plans')->insert([
            [
                'plan_type'     => \App\Plan::PLAN_TYPE_FREE,
                'plan_name'     => 'Free',
                'plan_max_free_listing' => null,
                'plan_max_featured_listing' => 0,
                'plan_features' => 'Email support',
                'plan_period'   => \App\Plan::PLAN_LIFETIME,
                'plan_price'    => 0,
                'plan_status'   => \App\Plan::PLAN_ENABLED,
                'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
            ],
            [
                'plan_type'     => \App\Plan::PLAN_TYPE_ADMIN,
                'plan_name'     => 'Admin',
                'plan_max_free_listing' => null,
                'plan_max_featured_listing' => null,
                'plan_features' => 'admin only plan',
                'plan_period'   => \App\Plan::PLAN_LIFETIME,
                'plan_price'    => 0,
                'plan_status'   => \App\Plan::PLAN_ENABLED,
                'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
            ],
            [
                'plan_type'     => \App\Plan::PLAN_TYPE_PAID,
                'plan_name'     => 'Monthly Premium',
                'plan_max_free_listing' => null,
                'plan_max_featured_listing' => 10,
                'plan_features' => 'Priority email support',
                'plan_period'   => \App\Plan::PLAN_MONTHLY,
                'plan_price'    => 9.99,
                'plan_status'   => \App\Plan::PLAN_ENABLED,
                'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
            ],
            [
                'plan_type'     => \App\Plan::PLAN_TYPE_PAID,
                'plan_name'     => 'Quarterly Premium',
                'plan_max_free_listing' => null,
                'plan_max_featured_listing' => 20,
                'plan_features' => 'Priority email support',
                'plan_period'   => \App\Plan::PLAN_QUARTERLY,
                'plan_price'    => 26.99,
                'plan_status'   => \App\Plan::PLAN_ENABLED,
                'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
            ],
            [
                'plan_type'     => \App\Plan::PLAN_TYPE_PAID,
                'plan_name'     => 'Yearly Premium',
                'plan_max_free_listing' => null,
                'plan_max_featured_listing' => 30,
                'plan_features' => 'Priority email support',
                'plan_period'   => \App\Plan::PLAN_YEARLY,
                'plan_price'    => 94.99,
                'plan_status'   => \App\Plan::PLAN_ENABLED,
                'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
            ],
        ]);
        $plan_id = DB::getPdo()->lastInsertId();

        /**
         * Admin user
         */
        $admin_name = $faker->firstName . ' ' . $faker->lastName;
        $admin_email = 'admin@mail.com';
        $admin_about = 'This is admin profile description, fee free to re-edit with your own description.';
        $admin_password = "12345678";

        DB::table('users')->insert([
            [
                'role_id'       => \App\Role::ADMIN_ROLE_ID,
                'name'          => $admin_name,
                'email'         => $admin_email,
                'user_about'    => $admin_about,
                'password'      => Hash::make($admin_password),
                'email_verified_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                'user_suspended' => \App\User::USER_NOT_SUSPENDED,
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);

        // assign admin admin type plan and subscription
        DB::table('subscriptions')->insert([
            [
                'user_id'       => 1,
                'plan_id'       => InstallSeeder::ADMIN_PLAN_ID,
                'subscription_start_date' => date("Y-m-d"),
                'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
            ]
        ]);
        /**
         * End admin user
         */

        for($i=0;$i<InstallSeeder::TOTAL_USERS;$i++)
        {
            /**
             * create an user
             */
            $user_name = $faker->firstName . ' ' . $faker->lastName;
            $username = strtolower($faker->firstName . "." . substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 4)), 0, 4));
            $user_email = $username . "@" . $faker->freeEmailDomain;
            $user_about = 'This is admin profile description, fee free to re-edit with your own description.';
            $user_password = "12345678";

            DB::table('users')->insert([
                [
                    'role_id'       => \App\Role::USER_ROLE_ID,
                    'name'          => $user_name,
                    'email'         => $user_email,
                    'user_about'    => $user_about,
                    'password'      => Hash::make($user_password),
                    'email_verified_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                    'user_suspended' => \App\User::USER_NOT_SUSPENDED,
                    'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                    'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                ]
            ]);

            $new_user_id = DB::getPdo()->lastInsertId();

            // assign admin admin type plan and subscription
            DB::table('subscriptions')->insert([
                [
                    'user_id'       => $new_user_id,
                    'plan_id'       => InstallSeeder::FREE_PLAN_ID,
                    'subscription_start_date' => date("Y-m-d"),
                    //'subscription_max_featured_listing' => 0,
                    //'subscription_max_free_listing' => null,
                    'created_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                    'updated_at'    => date("Y-m-d H:i:s", strtotime("-1 days")),
                ]
            ]);
            /**
             * End create an user
             */
        }

        /**
         * Categories
         */
        $categories = [

            // Restaurants
            [
                'id' => 1, 'category_name' => 'Restaurants', 'category_icon' => 'fas fa-utensils', 'category_parent_id' => null, 'items' => [],
            ],
            [
                'id' => 2, 'category_name' => 'Burgers', 'category_icon' => 'fas fa-hamburger', 'category_parent_id' => 1, 'items' => ['The Habit Burger Grill'],
            ],
            [
                'id' => 3, 'category_name' => 'Pizza', 'category_icon' => 'fas fa-pizza-slice', 'category_parent_id' => 1, 'items' => ['Round Table Pizza'],
            ],
            [
                'id' => 4, 'category_name' => 'Cafes', 'category_icon' => 'fas fa-coffee', 'category_parent_id' => 1, 'items' => ['Tickle Tree Cafe'],
            ],
            [
                'id' => 5, 'category_name' => 'Seafood', 'category_icon' => 'fas fa-fish', 'category_parent_id' => 1, 'items' => ['Red Lobster'],
            ],

            // Shopping
            [
                'id' => 6, 'category_name' => 'Shopping', 'category_icon' => 'fas fa-gifts', 'category_parent_id' => null, 'items' => [],
            ],
            [
                'id' => 7, 'category_name' => 'Fashion', 'category_icon' => 'fas fa-tshirt', 'category_parent_id' => 6, 'items' => ['J’adore Boutique'],
            ],
            [
                'id' => 8, 'category_name' => 'Wholesale', 'category_icon' => 'fas fa-warehouse', 'category_parent_id' => 6, 'items' => ['Costco'],
            ],
            [
                'id' => 9, 'category_name' => 'Flowers', 'category_icon' => 'fas fa-cannabis', 'category_parent_id' => 6, 'items' => ['Lavanda Flowers & Gifts'],
            ],

            // Health & Medical
            [
                'id' => 10, 'category_name' => 'Health & Medical', 'category_icon' => 'fas fa-cannabis', 'category_parent_id' => null, 'items' => [],
            ],
            [
                'id' => 11, 'category_name' => 'Dentists', 'category_icon' => 'fas fa-tooth', 'category_parent_id' => 10, 'items' => ['La Canada Smiles Dentistry'],
            ],
            [
                'id' => 12, 'category_name' => 'Optometrists', 'category_icon' => 'fas fa-glasses', 'category_parent_id' => 10, 'items' => ['Montrose Optometry'],
            ],

            // Professional Services
            [
                'id' => 13, 'category_name' => 'Professional Services', 'category_icon' => 'fas fa-briefcase', 'category_parent_id' => null, 'items' => [],
            ],
            [
                'id' => 14, 'category_name' => 'Accountants', 'category_icon' => 'fas fa-calculator', 'category_parent_id' => 13, 'items' => ['Brilliant Tax & Accounting Services'],
            ],
            [
                'id' => 15, 'category_name' => 'Lawyers', 'category_icon' => 'fas fa-gavel', 'category_parent_id' => 13, 'items' => ['Thomas M Lee Law Offices'],
            ],

            // Real Estate
            [
                'id' => 16, 'category_name' => 'Real Estate', 'category_icon' => 'fas fa-home', 'category_parent_id' => null, 'items' => [],
            ],
            [
                'id' => 17, 'category_name' => 'Apartments', 'category_icon' => 'fas fa-building', 'category_parent_id' => 16, 'items' => ['Amberwood Apartments'],
            ],
            [
                'id' => 18, 'category_name' => 'Mortgage Brokers', 'category_icon' => 'fas fa-hand-holding-usd', 'category_parent_id' => 16, 'items' => ['Stress Free Mortgage'],
            ],
            [
                'id' => 19, 'category_name' => 'Real Estate Agents', 'category_icon' => 'fas fa-address-card', 'category_parent_id' => 16, 'items' => ['Ben Kelly Real Estate'],
            ],

            // Local Services
            [
                'id' => 20, 'category_name' => 'Local Services', 'category_icon' => 'fas fa-map-marked-alt', 'category_parent_id' => null, 'items' => [],
            ],
            [
                'id' => 21, 'category_name' => 'Notaries', 'category_icon' => 'fas fa-stamp', 'category_parent_id' => 20, 'items' => ['DocSignings Mobile Notary'],
            ],
            [
                'id' => 22, 'category_name' => 'Pest Control', 'category_icon' => 'fas fa-pastafarianism', 'category_parent_id' => 20, 'items' => ['Hydrex Pest Control & Termite'],
            ],
            [
                'id' => 23, 'category_name' => 'Self Storage', 'category_icon' => 'fas fa-boxes', 'category_parent_id' => 20, 'items' => ['Andy’s Transfer & Storage'],
            ],
        ];

        /**
         * Start create categories
         */
        foreach($categories as $key => $category)
        {
            DB::table('categories')->insert([
                [
                    'id' => $category['id'],
                    'category_name' => $category['category_name'],
                    'category_slug' => str_slug($category['category_name']),
                    'category_icon' => $category['category_icon'],
                    'category_parent_id' => $category['category_parent_id'],
                    'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                    'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                ],
            ]);
        }
        /**
         * End create categories
         */

        /**
         * Custom fields
         */
        $custom_fields = [
            [
                'id' => 1,
                'custom_field_type' => \App\CustomField::TYPE_SELECT,
                'custom_field_name' => 'Price Range',
                'custom_field_seed_value' => '$,$$,$$$,$$$$',
                'custom_field_order' => 1,
                'category_ids' => [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23],
            ],
            [
                'id' => 2,
                'custom_field_type' => \App\CustomField::TYPE_MULTI_SELECT,
                'custom_field_name' => 'Parking',
                'custom_field_seed_value' => 'Street, Garage, Private Lot, Validated, Valet',
                'custom_field_order' => 5,
                'category_ids' => [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23],
            ],
            [
                'id' => 3,
                'custom_field_type' => \App\CustomField::TYPE_MULTI_SELECT,
                'custom_field_name' => 'Wi-Fi',
                'custom_field_seed_value' => 'Free, Paid',
                'custom_field_order' => 6,
                'category_ids' => [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23],
            ],
//            [
//                'id' => 4,
//                'custom_field_type' => \App\CustomField::TYPE_TEXT,
//                'custom_field_name' => 'Hours',
//                'custom_field_seed_value' => null,
//                'custom_field_order' => 9,
//                'category_ids' => [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23],
//            ],

            // General feature except restaurants
            [
                'id' => 5,
                'custom_field_type' => \App\CustomField::TYPE_MULTI_SELECT,
                'custom_field_name' => 'General Features',
                'custom_field_seed_value' => 'Offering a Deal, Accepts Credit Cards, Good for Kids, Wheelchair Accessible, By Appointment Only, Dogs Allowed, Sells Gift Certificates, Hot and New, Offers Military Discount, Gender Neutral Restrooms, Open to All',
                'custom_field_order' => 2,
                'category_ids' => [6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23],
            ],

            // Restaurants
            [
                'id' => 6,
                'custom_field_type' => \App\CustomField::TYPE_MULTI_SELECT,
                'custom_field_name' => 'General Features',
                'custom_field_seed_value' => 'Delivery, Takeout, Cash Back, Takes Reservations, Accepts Credit Cards, Outdoor Seating, Good for Kids, Good for Groups, Waiter Service, Wheelchair Accessible, Has TV, Dogs Allowed, Hot and New, Offers Military Discount, Gender Neutral Restrooms, Open to All',
                'custom_field_order' => 2,
                'category_ids' => [1,2,3,4,5],
            ],
            [
                'id' => 7,
                'custom_field_type' => \App\CustomField::TYPE_MULTI_SELECT,
                'custom_field_name' => 'Alcohol',
                'custom_field_seed_value' => 'Full Bar, Good For Happy Hour, Beer & Wine Only',
                'custom_field_order' => 3,
                'category_ids' => [1,2,3,4,5],
            ],
            [
                'id' => 8,
                'custom_field_type' => \App\CustomField::TYPE_MULTI_SELECT,
                'custom_field_name' => 'Meals Served',
                'custom_field_seed_value' => 'Breakfast, Brunch, Lunch, Dinner, Dessert, Late Night',
                'custom_field_order' => 4,
                'category_ids' => [1,2,3,4,5],
            ],
            [
                'id' => 9,
                'custom_field_type' => \App\CustomField::TYPE_LINK,
                'custom_field_name' => 'Start Order',
                'custom_field_seed_value' => null,
                'custom_field_order' => 7,
                'category_ids' => [1,2,3,4,5],
            ],
            [
                'id' => 10,
                'custom_field_type' => \App\CustomField::TYPE_LINK,
                'custom_field_name' => 'Reserve a Table',
                'custom_field_seed_value' => null,
                'custom_field_order' => 8,
                'category_ids' => [1,2,3,4,5],
            ],

            // Shopping
            [
                'id' => 11,
                'custom_field_type' => \App\CustomField::TYPE_LINK,
                'custom_field_name' => 'Online Shop',
                'custom_field_seed_value' => null,
                'custom_field_order' => 3,
                'category_ids' => [6,7,8,9],
            ],

            // Health & Medical
            [
                'id' => 12,
                'custom_field_type' => \App\CustomField::TYPE_LINK,
                'custom_field_name' => 'Request an Appointment',
                'custom_field_seed_value' => null,
                'custom_field_order' => 3,
                'category_ids' => [10,11,12],
            ],

            // Professional Services
            [
                'id' => 13,
                'custom_field_type' => \App\CustomField::TYPE_LINK,
                'custom_field_name' => 'Request a Consultation',
                'custom_field_seed_value' => null,
                'custom_field_order' => 3,
                'category_ids' => [13,14,15],
            ],

            // Real Estate
            [
                'id' => 14,
                'custom_field_type' => \App\CustomField::TYPE_LINK,
                'custom_field_name' => 'Request a Consultation',
                'custom_field_seed_value' => null,
                'custom_field_order' => 3,
                'category_ids' => [16,17,18,19],
            ],

            // Local Services
            [
                'id' => 15,
                'custom_field_type' => \App\CustomField::TYPE_LINK,
                'custom_field_name' => 'Request a Quote',
                'custom_field_seed_value' => null,
                'custom_field_order' => 3,
                'category_ids' => [20,21,22,23],
            ],
        ];

        /**
         * Start create custom fields
         */
        foreach($custom_fields as $key => $custom_field)
        {
            DB::table('custom_fields')->insert([
                [
                    'id' => $custom_field['id'],
                    'custom_field_type' => $custom_field['custom_field_type'],
                    'custom_field_name' => $custom_field['custom_field_name'],
                    'custom_field_seed_value' => $custom_field['custom_field_seed_value'],
                    'custom_field_order' => $custom_field['custom_field_order'],
                    'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                    'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                ],
            ]);

            $category_custom_field_relations = $custom_field['category_ids'];

            foreach($category_custom_field_relations as $key => $category_custom_field_relation_category_id)
            {
                DB::table('category_custom_field')->insert([
                    [
                        'category_id' => $category_custom_field_relation_category_id,
                        'custom_field_id' => $custom_field['id'],
                        'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                        'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                    ],
                ]);
            }
        }
        /**
         * End create custom fields
         */

        /**
         * Start create items
         */
        $item_categories_string = "";

        foreach($categories as $key_0 => $category)
        {
            if(is_null($category['category_parent_id']))
            {
                $item_categories_string = $category['category_name'] . " ";
            }

            if(count($category['items']) > 0)
            {
                $insert_items = $category['items'];

                foreach($insert_items as $key_1 => $insert_item)
                {
                    self::makeAnItem($insert_item, $category, $reviews, $item_categories_string . $category['category_name']);
                }
            }
        }
        /**
         * End create items
         */
    }

    private function makeAnItem($item_name, $category, $reviews, $item_categories_string)
    {
        $faker = \Faker\Factory::create();

        $item_social_facebook = 'https://facebook.com';
        $item_social_twitter = 'https://twitter.com';
        $item_social_linkedin = 'https://linkedin.com';

        $item_social_instagram = 'envato';
        $item_social_whatsapp = '61383766284';

        $country = Country::find(Setting::find(1)->setting_site_location_country_id);
        $country_id = $country->id;

        $states = \App\State::where('country_id', $country->id)->get();
        $state = $states->random();
        $state_id = $state->id;

        $cities = \App\City::where('state_id', $state->id)->get();
        $city = $cities->random();
        $city_id = $city->id;

        $item_lat = $city->city_lat;
        $item_lng = $city->city_lng;

        $item_postal_code = substr($faker->postcode, 0, 5);

        $item_featured = rand(0,10) > 6 ? \App\Item::ITEM_FEATURED : \App\Item::ITEM_NOT_FEATURED;
        $item_title = $item_name;

        $item_slug = str_slug($item_title);

        $item_description = $item_title . " in " . $city->city_name . ", " . $state->state_name . " style brisket, mouthwatering ribs, juicy pulled pork and Southern style sides. Satisfy your cravings at the Farmers Market Saturdays 9am-1pm. Call for catering only orders Mon-Fri 9-5pm.\r\n\r\nA good barbecue is only as good as it's Pitmaster. George has been an avid smoker for years but only began to take it on a serious note after receiving numerous accolades for his mouthwatering recipes and competition style smoking skills.";
        $item_address = $faker->streetAddress;
        $item_website = 'http://' . $faker->domainName;
        $item_phone = $faker->tollFreePhoneNumber;

        DB::table('items')->insert([
            [
                'user_id' => rand(1,InstallSeeder::TOTAL_LISTING_USERS+1),
                'item_status' => \App\Item::ITEM_PUBLISHED,
                'item_featured' => $item_featured,
                'item_featured_by_admin' => $item_featured == \App\Item::ITEM_FEATURED ? \App\Item::ITEM_FEATURED_BY_ADMIN : \App\Item::ITEM_NOT_FEATURED_BY_ADMIN,
                'item_title' => $item_title,
                'item_slug' => $item_slug,
                'item_description' => $item_description,
                'item_address' => $item_address,
                'item_address_hide' => rand(0,1),

                'state_id' => $state_id,
                'city_id' => $city_id,
                'country_id' => $country_id,

                'item_postal_code' => $item_postal_code,

                'item_website' => $item_website,
                'item_social_facebook' => $item_social_facebook,
                'item_social_twitter' => $item_social_twitter,
                'item_social_linkedin' => $item_social_linkedin,

                'item_social_instagram' => $item_social_instagram,
                'item_social_whatsapp' => $item_social_whatsapp,

                'item_phone' => $item_phone,

                'item_lat' => $item_lat,
                'item_lng' => $item_lng,

                'item_categories_string' => $item_categories_string,

                'item_location_str' => $city->city_name . ' ' . $state->state_name . ' ' . $country->country_name . ' ' . $item_postal_code,

                'item_type' => \App\Item::ITEM_TYPE_REGULAR,

                'item_hour_time_zone' => 'America/New_York',
                'item_hour_show_hours' => \App\Item::ITEM_HOUR_SHOW,

                'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
            ],
        ]);

        $new_item_id = DB::getPdo()->lastInsertId();

        /**
         * Start attach categories for this item
         */
        DB::table('category_item')->insert([
            [
                'category_id' => $category['id'],
                'item_id' => $new_item_id,
                'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
            ],
        ]);

        DB::table('category_item')->insert([
            [
                'category_id' => $category['category_parent_id'],
                'item_id' => $new_item_id,
                'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
            ],
        ]);
        /**
         * End attach categories for this item
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
                    'item_id' => $new_item_id,
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
                'item_id' => $new_item_id,
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

        /**
         * Start insert random reviews for this item
         */
        $num_reviews = rand(2, self::TOTAL_USERS - self::TOTAL_LISTING_USERS);
        $picked_reviews = array_rand($reviews, $num_reviews);
        $visitor_users_id = [];
        for($i=self::TOTAL_LISTING_USERS+2;$i<self::TOTAL_USERS+2;$i++)
        {
            $visitor_users_id[] = $i;
        }
        $picked_visitor_users_id = array_rand($visitor_users_id, $num_reviews);
        foreach($picked_reviews as $picked_reviews_key => $picked_reviews_value)
        {
            DB::table('reviews')->insert([
                [
                    'rating' => $reviews[$picked_reviews_value]['rating'],
                    'customer_service_rating' => $reviews[$picked_reviews_value]['customer_service_rating'],
                    'quality_rating' => $reviews[$picked_reviews_value]['quality_rating'],
                    'friendly_rating' => $reviews[$picked_reviews_value]['friendly_rating'],
                    'pricing_rating' => $reviews[$picked_reviews_value]['pricing_rating'],
                    'recommend' => $reviews[$picked_reviews_value]['rating'] > 3 ? \App\Item::ITEM_REVIEW_RECOMMEND_YES : \App\Item::ITEM_REVIEW_RECOMMEND_NO,
                    'department' => 'Sales',
                    'title' => $reviews[$picked_reviews_value]['title'],
                    'body' => $reviews[$picked_reviews_value]['body'],
                    'approved' => \App\Item::ITEM_REVIEW_APPROVED,
                    'reviewrateable_type' => "App\Item",
                    'reviewrateable_id' => $new_item_id,
                    'author_type' => "App\User",
                    'author_id' => $visitor_users_id[$picked_visitor_users_id[$picked_reviews_key]],

                    'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                    'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                ],
            ]);
        }
        /**
         * End insert random reviews for this item
         */

        /**
         * Start insert custom fields as item features
         */

        $custom_fields_ids = array();

        $custom_fields_ids_obj = DB::table('category_custom_field')
            ->select('custom_field_id')
            ->whereIn('category_id', [$category['category_parent_id'], $category['id']])
            ->groupBy('custom_field_id')
            ->get();

        foreach($custom_fields_ids_obj as $key => $custom_fields_id_obj)
        {
            $custom_fields_ids[] = $custom_fields_id_obj->custom_field_id;
        }

        $item_features_string = '';

        foreach($custom_fields_ids as $key => $custom_fields_id)
        {
            $item_feature_value = '';
            $custom_field_record = \App\CustomField::find($custom_fields_id);

            if($custom_field_record->custom_field_type == \App\CustomField::TYPE_MULTI_SELECT)
            {
                $item_feature_value = $custom_field_record->custom_field_seed_value;
                DB::table('item_features')->insert([
                    [
                        'item_id' => $new_item_id,
                        'custom_field_id' => $custom_fields_id,
                        'item_feature_value' => $item_feature_value,
                        'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                        'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                    ],
                ]);
            }
            elseif($custom_field_record->custom_field_type == \App\CustomField::TYPE_SELECT)
            {
                $seed_values_array = explode(',', $custom_field_record->custom_field_seed_value);

                $item_feature_value = $seed_values_array[rand(0,count($seed_values_array)-1)];
                DB::table('item_features')->insert([
                    [
                        'item_id' => $new_item_id,
                        'custom_field_id' => $custom_fields_id,
                        'item_feature_value' => $item_feature_value,
                        'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                        'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                    ],
                ]);
            }
            elseif($custom_field_record->custom_field_type == \App\CustomField::TYPE_LINK)
            {
                $item_feature_value = $faker->url;
                DB::table('item_features')->insert([
                    [
                        'item_id' => $new_item_id,
                        'custom_field_id' => $custom_fields_id,
                        'item_feature_value' => $item_feature_value,
                        'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                        'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
                    ],
                ]);
            }
//            elseif($custom_field_record->custom_field_type == \App\CustomField::TYPE_TEXT
//                && $custom_field_record->custom_field_name == 'Hours')
//            {
//                $item_feature_value = "Mon 11:00 am - 8:30 pm\r\nTue 11:00 am - 8:30 pm\r\nWed 11:00 am - 8:30 pm\r\nThu 11:00 am - 8:30 pm\r\nFri 11:00 am - 8:30 pm\r\nSat 11:00 am - 8:30 pm\r\nSun 11:00 am - 8:30 pm";
//
//                DB::table('item_features')->insert([
//                    [
//                        'item_id' => $new_item_id,
//                        'custom_field_id' => $custom_fields_id,
//                        'item_feature_value' => $item_feature_value,
//                        'created_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
//                        'updated_at' => date("Y-m-d H:i:s", strtotime("-1 days")),
//                    ],
//                ]);
//            }

            $item_features_string .= $item_feature_value . ' ';
        }

        $item_feature_update = \App\Item::find($new_item_id);
        $item_feature_update->item_features_string = $item_features_string;
        $item_feature_update->save();
        /**
         * End insert custom fields as item features
         */

        /**
         * Start sync item_average_rating
         */
        $item_feature_update->syncItemAverageRating();
        /**
         * End sync item_average_rating
         */
    }
}

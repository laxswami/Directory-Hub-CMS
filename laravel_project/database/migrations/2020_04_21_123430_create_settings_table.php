<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('setting_site_logo')->nullable();
            $table->string('setting_site_favicon')->nullable();

            $table->string('setting_site_name')->nullable();
            $table->string('setting_site_email')->nullable();
            $table->string('setting_site_phone')->nullable();

            $table->string('setting_site_address')->nullable();
            $table->string('setting_site_state')->nullable();
            $table->string('setting_site_city')->nullable();
            $table->string('setting_site_country')->nullable();
            $table->string('setting_site_postal_code')->nullable();

            $table->text('setting_site_about')->nullable();

            // default location of lat & lng on homepage
            $table->double('setting_site_location_lat', 18, 15);
            $table->double('setting_site_location_lng', 18, 15);
            $table->integer('setting_site_location_country_id');

            /**
             * Start SEO settings
             */
            $table->string('setting_site_seo_home_title')->nullable();
            $table->text('setting_site_seo_home_description')->nullable();
            $table->string('setting_site_seo_home_keywords')->nullable();
            /**
             * End SEO settings
             */

//            $table->string('setting_site_google_analytic')->nullable();
//            $table->string('setting_site_google_map_api_key')->nullable();

            /**
             * PayPal setting
             */
            $table->string('setting_site_paypal_mode')->default('sandbox')->comment('sandbox or live');
            $table->string('setting_site_paypal_payment_action')->default('Sale');
            $table->string('setting_site_paypal_currency')->default('USD');
            $table->string('setting_site_paypal_billing_type')->default('MerchantInitiatedBilling');
            $table->string('setting_site_paypal_notify_url')->default('');
            $table->string('setting_site_paypal_locale')->default('en_US');
            $table->boolean('setting_site_paypal_validate_ssl')->default(false);

            $table->string('setting_site_paypal_sandbox_username')->nullable();
            $table->string('setting_site_paypal_sandbox_password')->nullable();
            $table->string('setting_site_paypal_sandbox_secret')->nullable();
            $table->string('setting_site_paypal_sandbox_certificate')->nullable();
            $table->string('setting_site_paypal_sandbox_app_id')->nullable();

            $table->string('setting_site_paypal_live_username')->nullable();
            $table->string('setting_site_paypal_live_password')->nullable();
            $table->string('setting_site_paypal_live_secret')->nullable();
            $table->string('setting_site_paypal_live_certificate')->nullable();
            $table->string('setting_site_paypal_live_app_id')->nullable();
            /**
             * End PayPal setting
             */

            $table->integer('setting_page_about_enable')->default(0)->comment('0:off, 1:on');
            $table->longText('setting_page_about')->nullable();

            $table->integer('setting_page_terms_of_service_enable')->default(0)->comment('0:off, 1:on');
            $table->longText('setting_page_terms_of_service')->nullable();

            $table->integer('setting_page_privacy_policy_enable')->default(0)->comment('0:off, 1:on');
            $table->longText('setting_page_privacy_policy')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}

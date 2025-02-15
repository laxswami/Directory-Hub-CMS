<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Mail\Notification;
use App\Plan;
use App\Setting;
use App\SettingBankTransfer;
use App\SettingItem;
use App\SettingLanguage;
use App\SettingLicense;
use App\Subscription;
use App\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use DateTime;

class SettingController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     */
    public function editGeneralSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.setting.general-settings', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        // license check starts here
        $verify_response = do_license_verify(
            SettingLicense::LICENSE_API_HOST.SettingLicense::LICENSE_API_ROUTE_VERIFY,
            $settings->settingLicense->settings_license_purchase_code,
            $settings->settingLicense->settings_license_codecanyon_username,
            2);

        $verify_response_status = $verify_response['status'];
        $verify_response_message = $verify_response['message'];
        $verify_response_body = $verify_response['body'];

        if($verify_response_status)
        {
            if($verify_response_body->status_code == \App\SettingLicense::LICENSE_API_STATUS_CODE_SUCCESS)
            {
                eval($verify_response_body->proceed_passcode);

                return response()->view('backend.admin.setting.general.edit',
                    compact('settings', 'all_countries'));
            }
            else
            {
                return redirect()->route('admin.settings.license.edit');
            }
        }
        else
        {
            \Session::flash('flash_message', $verify_response_message);
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.index');
        }
        // license check ends here
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateGeneralSetting(Request $request)
    {
        $request->validate([
            'setting_site_name' => 'max:255',
            'setting_site_address' => 'max:255',
            'setting_site_state' => 'max:255',
            'setting_site_city' => 'max:255',
            'setting_site_country' => 'max:255',
            'setting_site_postal_code' => 'max:255',
            'setting_site_email' => 'max:255',
            'setting_site_phone' => 'max:255',

            'setting_site_location_lat' => 'required|numeric',
            'setting_site_location_lng' => 'required|numeric',
            'setting_site_location_country_id' => 'required|numeric',
            'setting_site_google_analytic_enabled' => 'nullable|numeric',
            'setting_site_google_analytic_tracking_id' => 'nullable|max:255',
            'setting_site_google_analytic_not_track_admin' => 'nullable|numeric',

            'setting_site_header_enabled' => 'nullable|numeric',
            'setting_site_header' => 'nullable',
            'setting_site_footer_enabled' => 'nullable|numeric',
            'setting_site_footer' => 'nullable',

            'settings_site_smtp_enabled' => 'nullable|numeric',
            'settings_site_smtp_sender_name' => 'nullable|max:255',
            'settings_site_smtp_sender_email' => 'nullable|email|max:255',
            'settings_site_smtp_host' => 'nullable|max:255',
            'settings_site_smtp_port' => 'nullable|numeric',
            'settings_site_smtp_encryption' => 'nullable|numeric',
            'settings_site_smtp_username' => 'nullable|max:255',
            'settings_site_smtp_password' => 'nullable|max:255',

            'setting_site_map' => 'required|numeric|in:1,2',
            'setting_site_map_google_api_key' => 'nullable|max:255',
            'setting_site_map_reload_homepage' => 'required|numeric|in:1,2',

            'setting_product_currency_symbol' => 'required|max:4',
        ]);

        $settings = app('site_global_settings');

        $selected_item_country = $request->setting_site_location_country_id;

        $validate_error = array();
        $country_exist = Country::find($selected_item_country);
        if(!$country_exist)
        {
            $validate_error['setting_site_location_country_id'] = __('prefer_country.alert.country-not-found');
        }
        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }

        // save website logo
        $website_logo_image = $request->setting_site_logo;
        $website_logo_image_name = $settings->setting_site_logo;
        if(!empty($website_logo_image)){

            $currentDate = Carbon::now()->toDateString();

            $website_logo_image_name = 'logo-'.$currentDate.'-'.uniqid().'.png';

            if(!Storage::disk('public')->exists('setting')){
                Storage::disk('public')->makeDirectory('setting');
            }
            if(Storage::disk('public')->exists('setting/' . $settings->setting_site_logo)){
                Storage::disk('public')->delete('setting/' . $settings->setting_site_logo);
            }

            $new_website_logo_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$website_logo_image)))->stream();

            Storage::disk('public')->put('setting/'.$website_logo_image_name, $new_website_logo_image);
        }
        $settings->setting_site_logo = $website_logo_image_name;

        // save favicon
        $website_favicon_image = $request->setting_site_favicon;
        $website_favicon_image_name = $settings->setting_site_favicon;
        if(!empty($website_favicon_image)){

            $currentDate = Carbon::now()->toDateString();

            $website_favicon_image_name = 'favicon-'.$currentDate.'-'.uniqid().'.png';

            if(!Storage::disk('public')->exists('setting')){
                Storage::disk('public')->makeDirectory('setting');
            }
            if(Storage::disk('public')->exists('setting/' . $settings->setting_site_favicon)){
                Storage::disk('public')->delete('setting/' . $settings->setting_site_favicon);
            }

            $new_website_favicon_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$website_favicon_image)))->stream();

            Storage::disk('public')->put('setting/'.$website_favicon_image_name, $new_website_favicon_image);
        }
        $settings->setting_site_favicon = $website_favicon_image_name;

        $settings->setting_site_name = $request->setting_site_name;
        $settings->setting_site_address = $request->setting_site_address;
        $settings->setting_site_state = $request->setting_site_state;
        $settings->setting_site_city = $request->setting_site_city;
        $settings->setting_site_country = $request->setting_site_country;

        $settings->setting_site_postal_code = $request->setting_site_postal_code;
        $settings->setting_site_email = $request->setting_site_email;
        $settings->setting_site_phone = $request->setting_site_phone;
        $settings->setting_site_about = $request->setting_site_about;

        $settings->setting_site_location_lat = $request->setting_site_location_lat;
        $settings->setting_site_location_lng = $request->setting_site_location_lng;
        $settings->setting_site_location_country_id = $selected_item_country;

        // seo
        $settings->setting_site_seo_home_title = $request->setting_site_seo_home_title;
        $settings->setting_site_seo_home_keywords = $request->setting_site_seo_home_keywords;
        $settings->setting_site_seo_home_description = $request->setting_site_seo_home_description;

        // google analytics
        $settings->setting_site_google_analytic_enabled = $request->setting_site_google_analytic_enabled == Setting::TRACKING_ON ? Setting::TRACKING_ON : Setting::TRACKING_OFF;
        $settings->setting_site_google_analytic_tracking_id = $request->setting_site_google_analytic_tracking_id;
        $settings->setting_site_google_analytic_not_track_admin = $request->setting_site_google_analytic_not_track_admin == Setting::NOT_TRACKING_ADMIN ? Setting::NOT_TRACKING_ADMIN: Setting::TRACKING_ADMIN;

        // site header code
        $settings->setting_site_header_enabled = empty($request->setting_site_header_enabled) ? Setting::SITE_HEADER_DISABLED : Setting::SITE_HEADER_ENABLED;
        $settings->setting_site_header = $request->setting_site_header;

        // site footer code
        $settings->setting_site_footer_enabled = empty($request->setting_site_footer_enabled) ? Setting::SITE_FOOTER_DISABLED : Setting::SITE_FOOTER_ENABLED;
        $settings->setting_site_footer = $request->setting_site_footer;

        // SMTP
        $settings->settings_site_smtp_enabled = empty($request->settings_site_smtp_enabled) ? Setting::SITE_SMTP_DISABLED : Setting::SITE_SMTP_ENABLED;
        $settings->settings_site_smtp_sender_name = $request->settings_site_smtp_sender_name;
        $settings->settings_site_smtp_sender_email = $request->settings_site_smtp_sender_email;
        $settings->settings_site_smtp_host = $request->settings_site_smtp_host;
        $settings->settings_site_smtp_port = $request->settings_site_smtp_port;
        $settings->settings_site_smtp_encryption = $request->settings_site_smtp_encryption;
        $settings->settings_site_smtp_username = $request->settings_site_smtp_username;
        $settings->settings_site_smtp_password = $request->settings_site_smtp_password;

        // Map
        $settings->setting_site_map = $request->setting_site_map;
        $settings->setting_site_map_google_api_key = empty($request->setting_site_map_google_api_key) ? null : $request->setting_site_map_google_api_key;
        $settings->setting_site_map_reload_homepage = $request->setting_site_map_reload_homepage == Setting::SITE_MAP_RELOAD_HOMEPAGE_YES ? Setting::SITE_MAP_RELOAD_HOMEPAGE_YES : Setting::SITE_MAP_RELOAD_HOMEPAGE_NO;

        // Currency symbol
        $settings->setting_product_currency_symbol = $request->setting_product_currency_symbol;

        $settings->save();

        \Session::flash('flash_message', __('alert.setting-general-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.general.edit');
    }

    public function editAboutPageSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.setting.about-page', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_page_about_settings = Setting::select(
            'setting_page_about_enable',
            'setting_page_about')->first();
        return response()->view('backend.admin.setting.about.edit',
            compact('all_page_about_settings'));
    }

    public function updateAboutPageSetting(Request $request)
    {
        $request->validate([
            'setting_page_about_enable' => 'numeric'
        ]);

        $setting = Setting::findOrFail(1);

        $setting->setting_page_about_enable = empty($request->setting_page_about_enable) ? Setting::ABOUT_PAGE_DISABLED : Setting::ABOUT_PAGE_ENABLED;
        $setting->setting_page_about = clean($request->setting_page_about);
        $setting->save();

        $all_page_about_settings = Setting::select(
            'setting_page_about_enable',
            'setting_page_about')->first();

        \Session::flash('flash_message', __('alert.setting-about-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.page.about.edit',
            compact('all_page_about_settings'));
    }

    public function editTermsOfServicePageSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.setting.terms-service-page', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_page_terms_of_service_settings = Setting::select(
            'setting_page_terms_of_service_enable',
            'setting_page_terms_of_service')->first();
        return response()->view('backend.admin.setting.terms-of-service.edit',
            compact('all_page_terms_of_service_settings'));
    }

    public function updateTermsOfServicePageSetting(Request $request)
    {
        $request->validate([
            'setting_page_terms_of_service_enable' => 'numeric'
        ]);

        $setting = Setting::findOrFail(1);

        $setting->setting_page_terms_of_service_enable = empty($request->setting_page_terms_of_service_enable) ? Setting::TERM_PAGE_DISABLED : Setting::TERM_PAGE_ENABLED;
        $setting->setting_page_terms_of_service = clean($request->setting_page_terms_of_service);
        $setting->save();

        $all_page_terms_of_service_settings = Setting::select(
            'setting_page_terms_of_service_enable',
            'setting_page_terms_of_service')->first();

        \Session::flash('flash_message', __('alert.setting-terms-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.page.terms-service.edit',
            compact('all_page_terms_of_service_settings'));
    }

    public function editPrivacyPolicyPageSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.setting.privacy-policy-page', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_page_privacy_policy_settings = Setting::select(
            'setting_page_privacy_policy_enable',
            'setting_page_privacy_policy')->first();
        return response()->view('backend.admin.setting.privacy-policy.edit',
            compact('all_page_privacy_policy_settings'));
    }

    public function updatePrivacyPolicyPageSetting(Request $request)
    {
        $request->validate([
            'setting_page_privacy_policy_enable' => 'numeric'
        ]);

        $setting = Setting::findOrFail(1);

        $setting->setting_page_privacy_policy_enable = empty($request->setting_page_privacy_policy_enable) ? Setting::PRIVACY_PAGE_DISABLED : Setting::PRIVACY_PAGE_ENABLED;
        $setting->setting_page_privacy_policy = clean($request->setting_page_privacy_policy);
        $setting->save();

        $all_page_privacy_policy_settings = Setting::select(
            'setting_page_privacy_policy_enable',
            'setting_page_privacy_policy')->first();

        \Session::flash('flash_message', __('alert.setting-privacy-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.page.privacy-policy.edit',
            compact('all_page_privacy_policy_settings'));
    }

    public function editRecaptchaSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('recaptcha.seo.edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_recaptcha_settings = Setting::select(
            'setting_site_recaptcha_login_enable',
            'setting_site_recaptcha_sign_up_enable',
            'setting_site_recaptcha_contact_enable',
            'setting_site_recaptcha_item_lead_enable',
            'setting_site_recaptcha_site_key',
            'setting_site_recaptcha_secret_key')->first();
        return response()->view('backend.admin.setting.recaptcha.edit',
            compact('all_recaptcha_settings'));
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateRecaptchaSetting(Request $request)
    {
        $request->validate([
            'setting_site_recaptcha_login_enable' => 'nullable|numeric',
            'setting_site_recaptcha_sign_up_enable' => 'nullable|numeric',
            'setting_site_recaptcha_contact_enable' => 'nullable|numeric',
            'setting_site_recaptcha_item_lead_enable' => 'nullable|numeric',
            'setting_site_recaptcha_site_key' => 'nullable|max:255',
            'setting_site_recaptcha_secret_key' => 'nullable|max:255',
        ]);

        $setting_site_recaptcha_login_enable = empty($request->setting_site_recaptcha_login_enable) ? Setting::SITE_RECAPTCHA_LOGIN_DISABLE : Setting::SITE_RECAPTCHA_LOGIN_ENABLE;
        $setting_site_recaptcha_sign_up_enable = empty($request->setting_site_recaptcha_sign_up_enable) ? Setting::SITE_RECAPTCHA_SIGN_UP_DISABLE : Setting::SITE_RECAPTCHA_SIGN_UP_ENABLE;
        $setting_site_recaptcha_contact_enable = empty($request->setting_site_recaptcha_contact_enable) ? Setting::SITE_RECAPTCHA_CONTACT_DISABLE : Setting::SITE_RECAPTCHA_CONTACT_ENABLE;
        $setting_site_recaptcha_item_lead_enable = empty($request->setting_site_recaptcha_item_lead_enable) ? Setting::SITE_RECAPTCHA_ITEM_LEAD_DISABLE : Setting::SITE_RECAPTCHA_ITEM_LEAD_ENABLE;

        $setting_site_recaptcha_site_key = $request->setting_site_recaptcha_site_key;
        $setting_site_recaptcha_secret_key = $request->setting_site_recaptcha_secret_key;

        $validate_error = array();
        if($setting_site_recaptcha_login_enable == Setting::SITE_RECAPTCHA_LOGIN_ENABLE
            || $setting_site_recaptcha_sign_up_enable == Setting::SITE_RECAPTCHA_SIGN_UP_ENABLE
            || $setting_site_recaptcha_contact_enable == Setting::SITE_RECAPTCHA_CONTACT_ENABLE
            || $setting_site_recaptcha_item_lead_enable == Setting::SITE_RECAPTCHA_ITEM_LEAD_ENABLE)
        {
            if(empty($setting_site_recaptcha_site_key))
            {
                $validate_error['setting_site_recaptcha_site_key'] = __('recaptcha.alert.site-key-required');
            }
            if(empty($setting_site_recaptcha_secret_key))
            {
                $validate_error['setting_site_recaptcha_secret_key'] = __('recaptcha.alert.site-secret-required');
            }
        }

        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }

        $setting = Setting::findOrFail(1);
        $setting->setting_site_recaptcha_login_enable = $setting_site_recaptcha_login_enable;
        $setting->setting_site_recaptcha_sign_up_enable = $setting_site_recaptcha_sign_up_enable;
        $setting->setting_site_recaptcha_contact_enable = $setting_site_recaptcha_contact_enable;
        $setting->setting_site_recaptcha_item_lead_enable = $setting_site_recaptcha_item_lead_enable;
        $setting->setting_site_recaptcha_site_key = $setting_site_recaptcha_site_key;
        $setting->setting_site_recaptcha_secret_key = $setting_site_recaptcha_secret_key;

        $setting->save();

        \Session::flash('flash_message', __('recaptcha.alert.edit-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.recaptcha.edit');
    }


    /**
     * Load edit page of PayPal payment gateway
     * @param Request $request
     * @return Response
     */
    public function editPayPalPaymentSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('paypal.seo.edit-paypal', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_paypal_settings = Setting::select(
            'setting_site_paypal_enable',
            'setting_site_paypal_mode',
            'setting_site_paypal_currency',
            'setting_site_paypal_sandbox_username',
            'setting_site_paypal_sandbox_password',
            'setting_site_paypal_sandbox_secret',
            'setting_site_paypal_sandbox_certificate',
            'setting_site_paypal_live_username',
            'setting_site_paypal_live_password',
            'setting_site_paypal_live_secret',
            'setting_site_paypal_live_certificate')->first();
        return response()->view('backend.admin.setting.payment.paypal.edit',
            compact('all_paypal_settings'));
    }

    /**
     * Update paypal payment gateway setting
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updatePayPalPaymentSetting(Request $request)
    {
        $request->validate([
            'setting_site_paypal_enable' => 'nullable|numeric',
            'setting_site_paypal_mode' => 'nullable|max:255',
            'setting_site_paypal_currency' => 'nullable|max:3',
            'setting_site_paypal_sandbox_username' => 'nullable|max:255',
            'setting_site_paypal_sandbox_password' => 'nullable|max:255',
            'setting_site_paypal_sandbox_secret' => 'nullable|max:255',
            'setting_site_paypal_sandbox_certificate' => 'nullable|max:255',
            'setting_site_paypal_live_username' => 'nullable|max:255',
            'setting_site_paypal_live_password' => 'nullable|max:255',
            'setting_site_paypal_live_secret' => 'nullable|max:255',
            'setting_site_paypal_live_certificate' => 'nullable|max:255',
        ]);

        $setting_site_paypal_enable = empty($request->setting_site_paypal_enable) ? Setting::SITE_PAYMENT_PAYPAL_DISABLE : Setting::SITE_PAYMENT_PAYPAL_ENABLE;
        $setting_site_paypal_mode = $request->setting_site_paypal_mode;

        $setting_site_paypal_currency = $request->setting_site_paypal_currency;

        $setting_site_paypal_sandbox_username = $request->setting_site_paypal_sandbox_username;
        $setting_site_paypal_sandbox_password = $request->setting_site_paypal_sandbox_password;
        $setting_site_paypal_sandbox_secret = $request->setting_site_paypal_sandbox_secret;
        $setting_site_paypal_sandbox_certificate = $request->setting_site_paypal_sandbox_certificate;

        $setting_site_paypal_live_username = $request->setting_site_paypal_live_username;
        $setting_site_paypal_live_password = $request->setting_site_paypal_live_password;
        $setting_site_paypal_live_secret = $request->setting_site_paypal_live_secret;
        $setting_site_paypal_live_certificate = $request->setting_site_paypal_live_certificate;

        $validate_error = array();
        if($setting_site_paypal_enable == Setting::SITE_PAYMENT_PAYPAL_ENABLE)
        {
            if($setting_site_paypal_mode == Setting::SITE_PAYMENT_PAYPAL_SANDBOX)
            {
                if(empty($setting_site_paypal_sandbox_username))
                {
                    $validate_error['setting_site_paypal_sandbox_username'] = __('paypal.alert.value-required');
                }
                if(empty($setting_site_paypal_sandbox_password))
                {
                    $validate_error['setting_site_paypal_sandbox_password'] = __('paypal.alert.value-required');
                }
                if(empty($setting_site_paypal_sandbox_secret))
                {
                    $validate_error['setting_site_paypal_sandbox_secret'] = __('paypal.alert.value-required');
                }
            }

            if($setting_site_paypal_mode == Setting::SITE_PAYMENT_PAYPAL_LIVE)
            {
                if(empty($setting_site_paypal_live_username))
                {
                    $validate_error['setting_site_paypal_live_username'] = __('paypal.alert.value-required');
                }
                if(empty($setting_site_paypal_live_password))
                {
                    $validate_error['setting_site_paypal_live_password'] = __('paypal.alert.value-required');
                }
                if(empty($setting_site_paypal_live_secret))
                {
                    $validate_error['setting_site_paypal_live_secret'] = __('paypal.alert.value-required');
                }
            }
        }
        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }

        $setting = Setting::findOrFail(1);
        $setting->setting_site_paypal_enable = $setting_site_paypal_enable;
        $setting->setting_site_paypal_mode = $setting_site_paypal_mode;
        $setting->setting_site_paypal_currency = $setting_site_paypal_currency;

        $setting->setting_site_paypal_sandbox_username = $setting_site_paypal_sandbox_username;
        $setting->setting_site_paypal_sandbox_password = $setting_site_paypal_sandbox_password;
        $setting->setting_site_paypal_sandbox_secret = $setting_site_paypal_sandbox_secret;
        $setting->setting_site_paypal_sandbox_certificate = $setting_site_paypal_sandbox_certificate;

        $setting->setting_site_paypal_live_username = $setting_site_paypal_live_username;
        $setting->setting_site_paypal_live_password = $setting_site_paypal_live_password;
        $setting->setting_site_paypal_live_secret = $setting_site_paypal_live_secret;
        $setting->setting_site_paypal_live_certificate = $setting_site_paypal_live_certificate;

        $setting->save();

        \Session::flash('flash_message', __('paypal.alert.update-paypal-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.payment.paypal.edit');

    }

    /**
     * Load Razorpay payment gateway setting page
     * @param Request $request
     * @return Response
     */
    public function editRazorpayPaymentSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('razorpay_setting.seo.edit-razorpay', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_razorpay_settings = Setting::select(
            'setting_site_razorpay_enable',
            'setting_site_razorpay_api_key',
            'setting_site_razorpay_api_secret',
            'setting_site_razorpay_currency')->first();
        return response()->view('backend.admin.setting.payment.razorpay.edit',
            compact('all_razorpay_settings'));
    }

    /**
     * Update Razorpay payment gateway setting
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateRazorpayPaymentSetting(Request $request)
    {
        $request->validate([
            'setting_site_razorpay_enable' => 'nullable|numeric',
            'setting_site_razorpay_api_key' => 'nullable|max:255',
            'setting_site_razorpay_api_secret' => 'nullable|max:255',
            'setting_site_razorpay_currency' => 'nullable|max:3',
        ]);

        $setting_site_razorpay_enable = empty($request->setting_site_razorpay_enable) ? Setting::SITE_PAYMENT_RAZORPAY_DISABLE : Setting::SITE_PAYMENT_RAZORPAY_ENABLE;
        $setting_site_razorpay_api_key = $request->setting_site_razorpay_api_key;
        $setting_site_razorpay_api_secret = $request->setting_site_razorpay_api_secret;
        $setting_site_razorpay_currency = $request->setting_site_razorpay_currency;

        $validate_error = array();
        if($setting_site_razorpay_enable == Setting::SITE_PAYMENT_RAZORPAY_ENABLE)
        {
            if(empty($setting_site_razorpay_api_key))
            {
                $validate_error['setting_site_razorpay_api_key'] = __('razorpay_setting.alert.value-required');
            }
            if(empty($setting_site_razorpay_api_secret))
            {
                $validate_error['setting_site_razorpay_api_secret'] = __('razorpay_setting.alert.value-required');
            }
            if(empty($setting_site_razorpay_currency))
            {
                $validate_error['setting_site_razorpay_currency'] = __('razorpay_setting.alert.value-required');
            }
        }

        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }

        $setting = Setting::findOrFail(1);
        $setting->setting_site_razorpay_enable = $setting_site_razorpay_enable;
        $setting->setting_site_razorpay_api_key = $setting_site_razorpay_api_key;
        $setting->setting_site_razorpay_api_secret = $setting_site_razorpay_api_secret;
        $setting->setting_site_razorpay_currency = $setting_site_razorpay_currency;

        $setting->save();

        \Session::flash('flash_message', __('razorpay_setting.alert.update-razorpay-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.payment.razorpay.edit');

    }

    /**
     * Load edit page of Stripe payment gateway
     * @param Request $request
     * @return Response
     */
    public function editStripePaymentSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('stripe.seo.edit-stripe', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_stripe_settings = Setting::select(
            'setting_site_stripe_enable',
            'setting_site_stripe_publishable_key',
            'setting_site_stripe_secret_key',
            'setting_site_stripe_webhook_signing_secret',
            'setting_site_stripe_currency')->first();
        return response()->view('backend.admin.setting.payment.stripe.edit',
            compact('all_stripe_settings'));
    }

    /**
     * Update Stripe payment gateway
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateStripePaymentSetting(Request $request)
    {
        $request->validate([
            'setting_site_stripe_enable' => 'nullable|numeric',
            'setting_site_stripe_publishable_key' => 'nullable|max:255',
            'setting_site_stripe_secret_key' => 'nullable|max:255',
            'setting_site_stripe_webhook_signing_secret' => 'nullable|max:255',
            'setting_site_stripe_currency' => 'nullable|max:3',
        ]);

        $setting_site_stripe_enable = empty($request->setting_site_stripe_enable) ? Setting::SITE_PAYMENT_STRIPE_DISABLE : Setting::SITE_PAYMENT_STRIPE_ENABLE;
        $setting_site_stripe_publishable_key = $request->setting_site_stripe_publishable_key;
        $setting_site_stripe_secret_key = $request->setting_site_stripe_secret_key;
        $setting_site_stripe_webhook_signing_secret = $request->setting_site_stripe_webhook_signing_secret;
        $setting_site_stripe_currency = $request->setting_site_stripe_currency;

        $validate_error = array();
        if($setting_site_stripe_enable == Setting::SITE_PAYMENT_STRIPE_ENABLE)
        {
            if(empty($setting_site_stripe_publishable_key))
            {
                $validate_error['setting_site_stripe_publishable_key'] = __('stripe.alert.value-required');
            }
            if(empty($setting_site_stripe_secret_key))
            {
                $validate_error['setting_site_stripe_secret_key'] = __('stripe.alert.value-required');
            }
            if(empty($setting_site_stripe_webhook_signing_secret))
            {
                $validate_error['setting_site_stripe_webhook_signing_secret'] = __('stripe.alert.value-required');
            }
            if(empty($setting_site_stripe_currency))
            {
                $validate_error['setting_site_stripe_currency'] = __('stripe.alert.value-required');
            }
        }

        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }

        $setting = Setting::findOrFail(1);
        $setting->setting_site_stripe_enable = $setting_site_stripe_enable;
        $setting->setting_site_stripe_publishable_key = $setting_site_stripe_publishable_key;
        $setting->setting_site_stripe_secret_key = $setting_site_stripe_secret_key;
        $setting->setting_site_stripe_webhook_signing_secret = $setting_site_stripe_webhook_signing_secret;
        $setting->setting_site_stripe_currency = $setting_site_stripe_currency;

        $setting->save();

        \Session::flash('flash_message', __('stripe.alert.update-stripe-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.payment.stripe.edit');

    }

    public function indexBankTransferPaymentSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('bank_transfer.seo.index', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_bank_transfers = SettingBankTransfer::all();

        return response()->view('backend.admin.setting.payment.bank-transfer.index',
            compact('all_bank_transfers'));
    }

    public function createBankTransferPaymentSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('bank_transfer.seo.create', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.setting.payment.bank-transfer.create');
    }

    public function storeBankTransferPaymentSetting(Request $request)
    {
        $request->validate([
            'setting_bank_transfer_bank_name' => 'required|max:255',
            'setting_bank_transfer_bank_account_info' => 'nullable|max:65535',
            'setting_bank_transfer_status' => 'nullable|numeric',
        ]);

        $setting_bank_transfer_bank_name = $request->setting_bank_transfer_bank_name;
        $setting_bank_transfer_bank_account_info = $request->setting_bank_transfer_bank_account_info;
        $setting_bank_transfer_status = empty($request->setting_bank_transfer_status) ? Setting::SITE_PAYMENT_BANK_TRANSFER_DISABLE : Setting::SITE_PAYMENT_BANK_TRANSFER_ENABLE;

        $setting_bank_transfer = new SettingBankTransfer;
        $setting_bank_transfer->setting_bank_transfer_bank_name = $setting_bank_transfer_bank_name;
        $setting_bank_transfer->setting_bank_transfer_bank_account_info = $setting_bank_transfer_bank_account_info;
        $setting_bank_transfer->setting_bank_transfer_status = $setting_bank_transfer_status;
        $setting_bank_transfer->save();

        \Session::flash('flash_message', __('bank_transfer.alert.created-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.payment.bank-transfer.edit',
            ['setting_bank_transfer' => $setting_bank_transfer]);
    }

    public function editBankTransferPaymentSetting(SettingBankTransfer $setting_bank_transfer)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('bank_transfer.seo.edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.setting.payment.bank-transfer.edit',
            compact('setting_bank_transfer'));
    }

    public function updateBankTransferPaymentSetting(Request $request, SettingBankTransfer $setting_bank_transfer)
    {
        $request->validate([
            'setting_bank_transfer_bank_name' => 'required|max:255',
            'setting_bank_transfer_bank_account_info' => 'nullable|max:65535',
            'setting_bank_transfer_status' => 'nullable|numeric',
        ]);

        $setting_bank_transfer_bank_name = $request->setting_bank_transfer_bank_name;
        $setting_bank_transfer_bank_account_info = $request->setting_bank_transfer_bank_account_info;
        $setting_bank_transfer_status = empty($request->setting_bank_transfer_status) ? Setting::SITE_PAYMENT_BANK_TRANSFER_DISABLE : Setting::SITE_PAYMENT_BANK_TRANSFER_ENABLE;

        $setting_bank_transfer->setting_bank_transfer_bank_name = $setting_bank_transfer_bank_name;
        $setting_bank_transfer->setting_bank_transfer_bank_account_info = $setting_bank_transfer_bank_account_info;
        $setting_bank_transfer->setting_bank_transfer_status = $setting_bank_transfer_status;
        $setting_bank_transfer->save();

        \Session::flash('flash_message', __('bank_transfer.alert.update-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.payment.bank-transfer.edit', ['setting_bank_transfer' => $setting_bank_transfer]);
    }

    public function destroyBankTransferPaymentSetting(SettingBankTransfer $setting_bank_transfer)
    {
        $setting_bank_transfer->delete();

        \Session::flash('flash_message', __('bank_transfer.alert.delete-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.payment.bank-transfer.index');
    }

    public function indexPendingBankTransferPayment(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('bank_transfer.seo.pending-invoices', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $pending_invoices = Invoice::whereIn('invoice_status', [Invoice::INVOICE_STATUS_PENDING, Invoice::INVOICE_STATUS_REJECT, Invoice::INVOICE_STATUS_PAID])
            ->where('invoice_pay_method', Subscription::PAY_METHOD_BANK_TRANSFER)
            ->get();

        return response()->view('backend.admin.setting.payment.bank-transfer.pending.index',
            compact('pending_invoices'));
    }

    public function showPendingBankTransferPayment(Invoice $invoice)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('bank_transfer.seo.view-pending-invoice', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $plan_id = $invoice->invoice_bank_transfer_future_plan_id;

        $plan = Plan::find($plan_id);

        if($plan)
        {
            return response()->view('backend.admin.setting.payment.bank-transfer.pending.show',
                compact('invoice', 'plan'));
        }
        else
        {
            \Session::flash('flash_message', __('bank_transfer.alert.plan-not-found'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.settings.payment.bank-transfer.pending.index');
        }
    }

    public function approveBankTransferPayment(Invoice $invoice)
    {
        if($invoice->invoice_pay_method == Subscription::PAY_METHOD_BANK_TRANSFER)
        {
            if($invoice->invoice_status == Invoice::INVOICE_STATUS_PAID)
            {
                \Session::flash('flash_message', __('bank_transfer.alert.paid-invoice'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('admin.settings.payment.bank-transfer.pending.show', ['invoice' => $invoice->id]);
            }

            $future_plan = Plan::find($invoice->invoice_bank_transfer_future_plan_id);

            if($future_plan)
            {
                $current_subscription = Subscription::find($invoice->subscription_id);

                if($current_subscription)
                {
                    $invoice->invoice_status = Invoice::INVOICE_STATUS_PAID;
                    $invoice->save();

                    $today = new DateTime('now');
                    if(!empty($current_subscription->subscription_end_date))
                    {
                        $today = new DateTime($current_subscription->subscription_end_date);
                    }
                    if($future_plan->plan_period == Plan::PLAN_MONTHLY)
                    {
                        $today->modify("+1 month");
                        $current_subscription->subscription_end_date = $today->format("Y-m-d");
                    }
                    if($future_plan->plan_period == Plan::PLAN_QUARTERLY)
                    {
                        $today->modify("+3 month");
                        $current_subscription->subscription_end_date = $today->format("Y-m-d");
                    }
                    if($future_plan->plan_period == Plan::PLAN_YEARLY)
                    {
                        $today->modify("+12 month");
                        $current_subscription->subscription_end_date = $today->format("Y-m-d");
                    }
                    $current_subscription->plan_id = $future_plan->id;

//                    $current_subscription->subscription_max_featured_listing = intval($future_plan->plan_max_featured_listing) >= 0 ? $future_plan->plan_max_featured_listing : null;
//                    $current_subscription->subscription_max_free_listing = intval($future_plan->plan_max_free_listing) >= 0 ? $future_plan->plan_max_free_listing : null;

                    $current_subscription->subscription_pay_method = Subscription::PAY_METHOD_BANK_TRANSFER;
                    $current_subscription->save();

                    \Session::flash('flash_message', __('bank_transfer.alert.transaction-approved'));
                    \Session::flash('flash_type', 'success');

                    return redirect()->route('admin.settings.payment.bank-transfer.pending.show', ['invoice' => $invoice->id]);
                }
                else
                {
                    \Session::flash('flash_message', __('bank_transfer.alert.subscription-not-found'));
                    \Session::flash('flash_type', 'danger');

                    return redirect()->route('admin.settings.payment.bank-transfer.pending.show', ['invoice' => $invoice->id]);
                }
            }
            else
            {
                \Session::flash('flash_message', __('bank_transfer.alert.plan-not-found'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('admin.settings.payment.bank-transfer.pending.show', ['invoice' => $invoice->id]);
            }
        }
        else
        {
            \Session::flash('flash_message', __('bank_transfer.alert.invoice-not-paid-by-bank-transfer'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.settings.payment.bank-transfer.pending.index');
        }
    }

    public function rejectBankTransferPayment(Invoice $invoice)
    {
        if($invoice->invoice_pay_method == Subscription::PAY_METHOD_BANK_TRANSFER)
        {
            if($invoice->invoice_status == Invoice::INVOICE_STATUS_PAID)
            {
                \Session::flash('flash_message', __('bank_transfer.alert.paid-invoice'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('admin.settings.payment.bank-transfer.pending.show', ['invoice' => $invoice->id]);
            }

            $invoice->invoice_status = Invoice::INVOICE_STATUS_REJECT;
            $invoice->save();

            \Session::flash('flash_message', __('bank_transfer.alert.transaction-rejected'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.settings.payment.bank-transfer.pending.show', ['invoice' => $invoice->id]);
        }
        else
        {
            \Session::flash('flash_message', __('bank_transfer.alert.invoice-not-paid-by-bank-transfer'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.settings.payment.bank-transfer.pending.index');
        }
    }

    public function editSitemapSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('sitemap.seo.edit-sitemap', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_sitemap_settings = Setting::select(
            'setting_site_sitemap_index_enable',
            'setting_site_sitemap_show_in_footer',
            'setting_site_sitemap_page_enable',
            'setting_site_sitemap_page_frequency',
            'setting_site_sitemap_page_format',
            'setting_site_sitemap_page_include_to_index',
            'setting_site_sitemap_category_enable',
            'setting_site_sitemap_category_frequency',
            'setting_site_sitemap_category_format',
            'setting_site_sitemap_category_include_to_index',
            'setting_site_sitemap_listing_enable',
            'setting_site_sitemap_listing_frequency',
            'setting_site_sitemap_listing_format',
            'setting_site_sitemap_listing_include_to_index',
            'setting_site_sitemap_post_enable',
            'setting_site_sitemap_post_frequency',
            'setting_site_sitemap_post_format',
            'setting_site_sitemap_post_include_to_index',
            'setting_site_sitemap_tag_enable',
            'setting_site_sitemap_tag_frequency',
            'setting_site_sitemap_tag_format',
            'setting_site_sitemap_tag_include_to_index',
            'setting_site_sitemap_topic_enable',
            'setting_site_sitemap_topic_frequency',
            'setting_site_sitemap_topic_format',
            'setting_site_sitemap_topic_include_to_index',
            'setting_site_sitemap_state_enable',
            'setting_site_sitemap_state_frequency',
            'setting_site_sitemap_state_format',
            'setting_site_sitemap_state_include_to_index',
            'setting_site_sitemap_city_enable',
            'setting_site_sitemap_city_frequency',
            'setting_site_sitemap_city_format',
            'setting_site_sitemap_city_include_to_index')->first();
        return response()->view('backend.admin.setting.sitemap.edit',
            compact('all_sitemap_settings'));
    }

    public function updateSitemapSetting(Request $request)
    {
        $request->validate([
            'setting_site_sitemap_index_enable' => 'nullable|numeric',
            'setting_site_sitemap_show_in_footer' => 'nullable|numeric',

            'setting_site_sitemap_page_enable' => 'nullable|numeric',
            'setting_site_sitemap_page_frequency' => 'required|in:always,hourly,daily,weekly,monthly,yearly,never',
            'setting_site_sitemap_page_format' => 'required|in:xml,html,txt,ror-rss,ror-rdf',
            'setting_site_sitemap_page_include_to_index' => 'nullable|numeric',

            'setting_site_sitemap_category_enable' => 'nullable|numeric',
            'setting_site_sitemap_category_frequency' => 'required|in:always,hourly,daily,weekly,monthly,yearly,never',
            'setting_site_sitemap_category_format' => 'required|in:xml,html,txt,ror-rss,ror-rdf',
            'setting_site_sitemap_category_include_to_index' => 'nullable|numeric',

            'setting_site_sitemap_listing_enable' => 'nullable|numeric',
            'setting_site_sitemap_listing_frequency' => 'required|in:always,hourly,daily,weekly,monthly,yearly,never',
            'setting_site_sitemap_listing_format' => 'required|in:xml,html,txt,ror-rss,ror-rdf',
            'setting_site_sitemap_listing_include_to_index' => 'nullable|numeric',

            'setting_site_sitemap_post_enable' => 'nullable|numeric',
            'setting_site_sitemap_post_frequency' => 'required|in:always,hourly,daily,weekly,monthly,yearly,never',
            'setting_site_sitemap_post_format' => 'required|in:xml,html,txt,ror-rss,ror-rdf',
            'setting_site_sitemap_post_include_to_index' => 'nullable|numeric',

            'setting_site_sitemap_tag_enable' => 'nullable|numeric',
            'setting_site_sitemap_tag_frequency' => 'required|in:always,hourly,daily,weekly,monthly,yearly,never',
            'setting_site_sitemap_tag_format' => 'required|in:xml,html,txt,ror-rss,ror-rdf',
            'setting_site_sitemap_tag_include_to_index' => 'nullable|numeric',

            'setting_site_sitemap_topic_enable' => 'nullable|numeric',
            'setting_site_sitemap_topic_frequency' => 'required|in:always,hourly,daily,weekly,monthly,yearly,never',
            'setting_site_sitemap_topic_format' => 'required|in:xml,html,txt,ror-rss,ror-rdf',
            'setting_site_sitemap_topic_include_to_index' => 'nullable|numeric',

            'setting_site_sitemap_state_enable' => 'nullable|numeric',
            'setting_site_sitemap_state_frequency' => 'required|in:always,hourly,daily,weekly,monthly,yearly,never',
            'setting_site_sitemap_state_format' => 'required|in:xml,html,txt,ror-rss,ror-rdf',
            'setting_site_sitemap_state_include_to_index' => 'nullable|numeric',

            'setting_site_sitemap_city_enable' => 'nullable|numeric',
            'setting_site_sitemap_city_frequency' => 'required|in:always,hourly,daily,weekly,monthly,yearly,never',
            'setting_site_sitemap_city_format' => 'required|in:xml,html,txt,ror-rss,ror-rdf',
            'setting_site_sitemap_city_include_to_index' => 'nullable|numeric',
        ]);

        $setting_site_sitemap_index_enable = empty($request->setting_site_sitemap_index_enable) ? Setting::SITE_SITEMAP_INDEX_DISABLE : Setting::SITE_SITEMAP_INDEX_ENABLE;

        $setting_site_sitemap_show_in_footer = empty($request->setting_site_sitemap_show_in_footer) ? Setting::SITE_SITEMAP_NOT_SHOW_IN_FOOTER : Setting::SITE_SITEMAP_SHOW_IN_FOOTER;

        $setting_site_sitemap_page_enable = empty($request->setting_site_sitemap_page_enable) ? Setting::SITE_SITEMAP_PAGE_DISABLE : Setting::SITE_SITEMAP_PAGE_ENABLE;
        $setting_site_sitemap_page_frequency = $request->setting_site_sitemap_page_frequency;
        $setting_site_sitemap_page_format = $request->setting_site_sitemap_page_format;
        $setting_site_sitemap_page_include_to_index = empty($request->setting_site_sitemap_page_include_to_index) ? Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX : Setting::SITE_SITEMAP_INCLUDE_TO_INDEX;

        $setting_site_sitemap_category_enable = empty($request->setting_site_sitemap_category_enable) ? Setting::SITE_SITEMAP_CATEGORY_DISABLE : Setting::SITE_SITEMAP_CATEGORY_ENABLE;
        $setting_site_sitemap_category_frequency = $request->setting_site_sitemap_category_frequency;
        $setting_site_sitemap_category_format = $request->setting_site_sitemap_category_format;
        $setting_site_sitemap_category_include_to_index = empty($request->setting_site_sitemap_category_include_to_index) ? Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX : Setting::SITE_SITEMAP_INCLUDE_TO_INDEX;

        $setting_site_sitemap_listing_enable = empty($request->setting_site_sitemap_listing_enable) ? Setting::SITE_SITEMAP_LISTING_DISABLE : Setting::SITE_SITEMAP_LISTING_ENABLE;
        $setting_site_sitemap_listing_frequency = $request->setting_site_sitemap_listing_frequency;
        $setting_site_sitemap_listing_format = $request->setting_site_sitemap_listing_format;
        $setting_site_sitemap_listing_include_to_index = empty($request->setting_site_sitemap_listing_include_to_index) ? Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX : Setting::SITE_SITEMAP_INCLUDE_TO_INDEX;

        $setting_site_sitemap_post_enable = empty($request->setting_site_sitemap_post_enable) ? Setting::SITE_SITEMAP_POST_DISABLE : Setting::SITE_SITEMAP_POST_ENABLE;
        $setting_site_sitemap_post_frequency = $request->setting_site_sitemap_post_frequency;
        $setting_site_sitemap_post_format = $request->setting_site_sitemap_post_format;
        $setting_site_sitemap_post_include_to_index = empty($request->setting_site_sitemap_post_include_to_index) ? Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX : Setting::SITE_SITEMAP_INCLUDE_TO_INDEX;

        $setting_site_sitemap_tag_enable = empty($request->setting_site_sitemap_tag_enable) ? Setting::SITE_SITEMAP_TAG_DISABLE : Setting::SITE_SITEMAP_TAG_ENABLE;
        $setting_site_sitemap_tag_frequency = $request->setting_site_sitemap_tag_frequency;
        $setting_site_sitemap_tag_format = $request->setting_site_sitemap_tag_format;
        $setting_site_sitemap_tag_include_to_index = empty($request->setting_site_sitemap_tag_include_to_index) ? Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX : Setting::SITE_SITEMAP_INCLUDE_TO_INDEX;

        $setting_site_sitemap_topic_enable = empty($request->setting_site_sitemap_topic_enable) ? Setting::SITE_SITEMAP_TOPIC_DISABLE : Setting::SITE_SITEMAP_TOPIC_ENABLE;
        $setting_site_sitemap_topic_frequency = $request->setting_site_sitemap_topic_frequency;
        $setting_site_sitemap_topic_format = $request->setting_site_sitemap_topic_format;
        $setting_site_sitemap_topic_include_to_index = empty($request->setting_site_sitemap_topic_include_to_index) ? Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX : Setting::SITE_SITEMAP_INCLUDE_TO_INDEX;

        $setting_site_sitemap_state_enable = empty($request->setting_site_sitemap_state_enable) ? Setting::SITE_SITEMAP_STATE_DISABLE : Setting::SITE_SITEMAP_STATE_ENABLE;
        $setting_site_sitemap_state_frequency = $request->setting_site_sitemap_state_frequency;
        $setting_site_sitemap_state_format = $request->setting_site_sitemap_state_format;
        $setting_site_sitemap_state_include_to_index = empty($request->setting_site_sitemap_state_include_to_index) ? Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX : Setting::SITE_SITEMAP_INCLUDE_TO_INDEX;

        $setting_site_sitemap_city_enable = empty($request->setting_site_sitemap_city_enable) ? Setting::SITE_SITEMAP_CITY_DISABLE : Setting::SITE_SITEMAP_CITY_ENABLE;
        $setting_site_sitemap_city_frequency = $request->setting_site_sitemap_city_frequency;
        $setting_site_sitemap_city_format = $request->setting_site_sitemap_city_format;
        $setting_site_sitemap_city_include_to_index = empty($request->setting_site_sitemap_city_include_to_index) ? Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX : Setting::SITE_SITEMAP_INCLUDE_TO_INDEX;

        $setting = Setting::findOrFail(1);
        $setting->setting_site_sitemap_index_enable = $setting_site_sitemap_index_enable;

        $setting->setting_site_sitemap_show_in_footer = $setting_site_sitemap_show_in_footer;

        $setting->setting_site_sitemap_page_enable = $setting_site_sitemap_page_enable;
        $setting->setting_site_sitemap_page_frequency = $setting_site_sitemap_page_frequency;
        $setting->setting_site_sitemap_page_format = $setting_site_sitemap_page_format;
        $setting->setting_site_sitemap_page_include_to_index = $setting_site_sitemap_page_include_to_index;

        $setting->setting_site_sitemap_category_enable = $setting_site_sitemap_category_enable;
        $setting->setting_site_sitemap_category_frequency = $setting_site_sitemap_category_frequency;
        $setting->setting_site_sitemap_category_format = $setting_site_sitemap_category_format;
        $setting->setting_site_sitemap_category_include_to_index = $setting_site_sitemap_category_include_to_index;

        $setting->setting_site_sitemap_listing_enable = $setting_site_sitemap_listing_enable;
        $setting->setting_site_sitemap_listing_frequency = $setting_site_sitemap_listing_frequency;
        $setting->setting_site_sitemap_listing_format = $setting_site_sitemap_listing_format;
        $setting->setting_site_sitemap_listing_include_to_index = $setting_site_sitemap_listing_include_to_index;

        $setting->setting_site_sitemap_post_enable = $setting_site_sitemap_post_enable;
        $setting->setting_site_sitemap_post_frequency = $setting_site_sitemap_post_frequency;
        $setting->setting_site_sitemap_post_format = $setting_site_sitemap_post_format;
        $setting->setting_site_sitemap_post_include_to_index = $setting_site_sitemap_post_include_to_index;

        $setting->setting_site_sitemap_tag_enable = $setting_site_sitemap_tag_enable;
        $setting->setting_site_sitemap_tag_frequency = $setting_site_sitemap_tag_frequency;
        $setting->setting_site_sitemap_tag_format = $setting_site_sitemap_tag_format;
        $setting->setting_site_sitemap_tag_include_to_index = $setting_site_sitemap_tag_include_to_index;

        $setting->setting_site_sitemap_topic_enable = $setting_site_sitemap_topic_enable;
        $setting->setting_site_sitemap_topic_frequency = $setting_site_sitemap_topic_frequency;
        $setting->setting_site_sitemap_topic_format = $setting_site_sitemap_topic_format;
        $setting->setting_site_sitemap_topic_include_to_index = $setting_site_sitemap_topic_include_to_index;

        $setting->setting_site_sitemap_state_enable = $setting_site_sitemap_state_enable;
        $setting->setting_site_sitemap_state_frequency = $setting_site_sitemap_state_frequency;
        $setting->setting_site_sitemap_state_format = $setting_site_sitemap_state_format;
        $setting->setting_site_sitemap_state_include_to_index = $setting_site_sitemap_state_include_to_index;

        $setting->setting_site_sitemap_city_enable = $setting_site_sitemap_city_enable;
        $setting->setting_site_sitemap_city_frequency = $setting_site_sitemap_city_frequency;
        $setting->setting_site_sitemap_city_format = $setting_site_sitemap_city_format;
        $setting->setting_site_sitemap_city_include_to_index = $setting_site_sitemap_city_include_to_index;

        $setting->save();

        \Session::flash('flash_message', __('sitemap.alert.updated-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.sitemap.edit');

    }

    public function editCacheSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('setting_cache.seo.edit-setting-cache', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */
        $setting_site_last_cached_at = $settings->setting_site_last_cached_at;

        return response()->view('backend.admin.setting.cache.edit',
            compact('setting_site_last_cached_at'));
    }


    public function updateCacheSetting(Request $request)
    {
        $settings = app('site_global_settings');

        clear_website_cache();
        generate_website_route_cache();
        generate_website_view_cache();

        $dt = new DateTime();
        $settings->setting_site_last_cached_at = $dt->format('Y-m-d H:i:s');
        $settings->save();

        \Session::flash('flash_message', __('setting_cache.alert.updated-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.cache.edit');
    }

    public function deleteCacheSetting(Request $request)
    {
        $settings = app('site_global_settings');

        clear_website_cache();

        $settings->setting_site_last_cached_at = null;
        $settings->save();

        \Session::flash('flash_message', __('setting_cache.alert.deleted-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.cache.edit');
    }

    /**
     * Edit payumoney setting
     * @param Request $request
     * @return Response
     */
    public function editPayumoneyPaymentSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('payumoney.seo.edit-payumoney', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.setting.payment.payumoney.edit');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updatePayumoneyPaymentSetting(Request $request)
    {
        $request->validate([
            'setting_site_payumoney_enable' => 'nullable|numeric',
            'setting_site_payumoney_mode' => 'nullable|max:255',
            'setting_site_payumoney_merchant_key' => 'nullable|max:255',
            'setting_site_payumoney_salt' => 'nullable|max:255',
        ]);

        $setting_site_payumoney_enable = $request->setting_site_payumoney_enable;
        $setting_site_payumoney_mode = $request->setting_site_payumoney_mode;
        $setting_site_payumoney_merchant_key = $request->setting_site_payumoney_merchant_key;
        $setting_site_payumoney_salt = $request->setting_site_payumoney_salt;

        $validate_error = array();
        if($setting_site_payumoney_enable == Setting::SITE_PAYMENT_PAYUMONEY_ENABLE)
        {
            if(empty($setting_site_payumoney_mode))
            {
                $validate_error['setting_site_payumoney_mode'] = __('payumoney.alert.value-required');
            }
            if(empty($setting_site_payumoney_merchant_key))
            {
                $validate_error['setting_site_payumoney_merchant_key'] = __('payumoney.alert.value-required');
            }
            if(empty($setting_site_payumoney_salt))
            {
                $validate_error['setting_site_payumoney_salt'] = __('payumoney.alert.value-required');
            }
        }
        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }

        $settings = app('site_global_settings');
        $settings->setting_site_payumoney_enable = $setting_site_payumoney_enable;
        $settings->setting_site_payumoney_mode = $setting_site_payumoney_mode;
        $settings->setting_site_payumoney_merchant_key = $setting_site_payumoney_merchant_key;
        $settings->setting_site_payumoney_salt = $setting_site_payumoney_salt;
        $settings->save();

        \Session::flash('flash_message', __('payumoney.alert.updated-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.payment.payumoney.edit');
    }

    public function editItemSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('theme_directory_hub.setting.seo.edit-item', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.setting.item.edit',
            compact('settings'));
    }

    public function updateItemSetting(Request $request)
    {
        $request->validate([
            'setting_item_max_gallery_photos' => 'required|numeric|between:1,20',
            'setting_item_auto_approval_enable' => 'required|numeric|in:0,1',
        ]);

        $setting_item_max_gallery_photos = $request->setting_item_max_gallery_photos;
        $setting_item_auto_approval_enable = empty($request->setting_item_auto_approval_enable) ? SettingItem::SITE_ITEM_AUTO_APPROVAL_DISABLED : SettingItem::SITE_ITEM_AUTO_APPROVAL_ENABLED;

        $settings = app('site_global_settings');
        $settings_items = $settings->settingItem()->first();

        $settings_items->setting_item_max_gallery_photos = $setting_item_max_gallery_photos;
        $settings_items->setting_item_auto_approval_enable = $setting_item_auto_approval_enable;
        $settings_items->save();

        \Session::flash('flash_message', __('theme_directory_hub.setting.alert.setting-item-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.item.edit');
    }

    public function editProductSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('products.seo.edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.setting.product.edit',
            compact('settings'));
    }

    public function updateProductSetting(Request $request)
    {
        /**
         * Start form validation
         */
        $request->validate([
            'setting_product_max_gallery_photos' => 'required|numeric|between:1,20',
            'setting_product_auto_approval_enable' => 'required|numeric|in:0,1',
        ]);

        $setting_product_max_gallery_photos = $request->setting_product_max_gallery_photos;
        $setting_product_auto_approval_enable = empty($request->setting_product_auto_approval_enable) ? Setting::SITE_PRODUCT_AUTO_APPROVAL_DISABLED : Setting::SITE_PRODUCT_AUTO_APPROVAL_ENABLED;
        /**
         * End form validation
         */

        $settings = app('site_global_settings');
        $settings->setting_product_max_gallery_photos = $setting_product_max_gallery_photos;
        $settings->setting_product_auto_approval_enable = $setting_product_auto_approval_enable;

        $settings->save();

        \Session::flash('flash_message', __('products.alert.product-setting-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.product.edit');
    }

    public function editSessionSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('setting_session.seo.edit-setting-session', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $setting_session = env('SESSION_DRIVER', 'file');

        return response()->view('backend.admin.setting.session.edit',
            compact('setting_session'));
    }

    public function updateSessionSetting(Request $request)
    {
        /**
         * Start form validation
         */
        $request->validate([
            'setting_session' => 'required|max:255|in:file,cookie,database',
        ]);

        $setting_session = $request->setting_session;
        /**
         * End form validation
         */

        $path = base_path('.env');

        if (file_exists($path)) {

            $old_setting_session = env('SESSION_DRIVER', 'file');

            file_put_contents($path, str_replace(
                'SESSION_DRIVER='.$old_setting_session, 'SESSION_DRIVER='.$setting_session, file_get_contents($path)
            ));
        }

        \Session::flash('flash_message', __('setting_session.alert.session-updated-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.session.edit');
    }

    public function editLanguageSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('setting_language.language.seo.edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.setting.language.edit',
            compact('settings'));
    }

    public function updateLanguageSetting(Request $request)
    {
        $request->validate([
            'setting_language_default_language' => 'required|max:255',
            'setting_languages' => 'required',
        ]);

        $setting_language_default_language = $request->setting_language_default_language;
        $setting_languages = $request->setting_languages;

        if(!in_array($setting_language_default_language, $setting_languages))
        {
            throw ValidationException::withMessages([
                'setting_language_default_language' => __('setting_language.language.alert.default-not-in-available-languages')
            ]);
        }
        else
        {
            $settings = app('site_global_settings');
            $settings_languages = $settings->settingLanguage()->first();

            $settings_languages->setting_language_default_language = $setting_language_default_language;

            foreach(Setting::LANGUAGES as $settings_languages_key => $language)
            {
                if(in_array($settings_languages_key, $setting_languages))
                {
                    $settings_languages->$language = SettingLanguage::LANGUAGE_ENABLE;
                }
                else
                {
                    $settings_languages->$language = SettingLanguage::LANGUAGE_DISABLE;

                    // update users table if any user set the prefer language that disabled, set it back to the
                    // default language.
                    $user_prefer_language_exist = User::where('user_prefer_language', $settings_languages_key)->count();

                    if($user_prefer_language_exist > 0)
                    {
                        $update_user_prefer_language = User::where('user_prefer_language', $settings_languages_key)
                            ->update(['user_prefer_language' => $setting_language_default_language]);
                    }
                }
            }

            $settings_languages->save();

            \Session::flash('flash_message', __('setting_language.language.alert.updated-success'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.settings.language.edit');
        }
    }

    public function testSmtpSetting(Request $request)
    {
        $request->validate([
            'smtp_receiver_email' => 'required|email',
        ]);

        $settings = app('site_global_settings');

        $smtp_receiver_email = $request->smtp_receiver_email;

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
        else
        {
            \Session::flash('flash_message', __('category_index.test-smtp.enable-smtp-notify'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.settings.general.edit');
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

        $faker = \Faker\Factory::create();

        // send an email notification to $smtp_receiver_email
        $email_subject = __('category_index.test-smtp.email-subject');
        $email_notify_message = [
            __('category_index.test-smtp.email-body'),
            $faker->sentence(30),
        ];

        try
        {
            Mail::to($smtp_receiver_email)->send(
                new Notification(
                    $email_subject,
                    null,
                    null,
                    $email_notify_message
                )
            );

            \Session::flash('flash_message', __('category_index.test-smtp.success-notify') . ' ' . $smtp_receiver_email . '.');
            \Session::flash('flash_type', 'success');
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage() . "\n" . $e->getTraceAsString());

            \Session::flash('flash_message', __('category_index.test-smtp.error-notify') . ' ' . $e->getMessage());
            \Session::flash('flash_type', 'danger');
        }

        return redirect()->route('admin.settings.general.edit');
    }


    public function editMaintenanceSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('maintenance_mode.seo.setting-maintenance', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.setting.maintenance.edit',
            compact('settings'));
    }

    public function updateMaintenanceSetting(Request $request)
    {
        $request->validate([
            'setting_site_maintenance_mode' => 'required|numeric|in:1,2',
        ]);

        $setting_site_maintenance_mode = $request->setting_site_maintenance_mode == Setting::SITE_MAINTENANCE_MODE_OFF ? Setting::SITE_MAINTENANCE_MODE_OFF : Setting::SITE_MAINTENANCE_MODE_ON;

        $settings = app('site_global_settings');
        $settings->setting_site_maintenance_mode = $setting_site_maintenance_mode;
        $settings->save();


        \Session::flash('flash_message', __('maintenance_mode.alert.maintenance-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.maintenance.edit');
    }

    public function editLicenseSetting(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('license_verify.seo.edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        /**
         * Start call license verification API
         */
        $verify_response = do_license_verify(
            SettingLicense::LICENSE_API_HOST.SettingLicense::LICENSE_API_ROUTE_SHOW,
            $settings->settingLicense->settings_license_purchase_code,
            $settings->settingLicense->settings_license_codecanyon_username,
            6);

        $verify_response_status = $verify_response['status'];
        $verify_response_message = $verify_response['message'];
        $verify_response_body = $verify_response['body'];
        /**
         * End call license verification API
         */

        return response()->view('backend.admin.setting.license.edit',
            compact('settings', 'verify_response_status', 'verify_response_message', 'verify_response_body'));
    }

    public function updateLicenseSetting(Request $request)
    {
        $request->validate([
            'settings_license_purchase_code' => 'nullable|max:255',
            'settings_license_codecanyon_username' => 'nullable|max:255',
        ]);

        $settings_license_purchase_code = $request->settings_license_purchase_code;
        $settings_license_codecanyon_username = $request->settings_license_codecanyon_username;

        $settings = app('site_global_settings');

        $setting_license = $settings->settingLicense()->first();

        $setting_license->settings_license_purchase_code = $settings_license_purchase_code;
        $setting_license->settings_license_codecanyon_username = $settings_license_codecanyon_username;
        $setting_license->save();

        /**
         * Start register license
         */
        $verify_response = do_license_verify(
            SettingLicense::LICENSE_API_HOST.SettingLicense::LICENSE_API_ROUTE_VERIFY,
            $settings_license_purchase_code,
            $settings_license_codecanyon_username,
            5);

        $verify_response_status = $verify_response['status'];
        $verify_response_message = $verify_response['message'];
        $verify_response_body = $verify_response['body'];

        if($verify_response_status)
        {

            if($verify_response_body->status_code == \App\SettingLicense::LICENSE_API_STATUS_CODE_SUCCESS)
            {
                \Session::flash('flash_message', $verify_response_body->status_message);
                \Session::flash('flash_type', 'success');
            }
            else
            {
                \Session::flash('flash_message', $verify_response_body->status_message);
                \Session::flash('flash_type', 'danger');
            }
        }
        else
        {
            \Session::flash('flash_message', $verify_response_message);
            \Session::flash('flash_type', 'danger');
        }
        /**
         * Start end register license
         */

        return redirect()->route('admin.settings.license.edit');
    }

    public function updateDomainVerifyTokenLicenseSetting(Request $request)
    {
        $request->validate([
            'settings_license_domain_verify_token' => 'nullable|max:255',
        ]);

        $settings_license_domain_verify_token = $request->settings_license_domain_verify_token;

        $settings = app('site_global_settings');

        $setting_license = $settings->settingLicense()->first();
        $setting_license->settings_license_domain_verify_token = $settings_license_domain_verify_token;
        $setting_license->save();

        \Session::flash('flash_message', __('license_verify.alert.domain-verify-token-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.license.edit');
    }

    public function revokeDomainLicenseSetting(Request $request, $domain_host)
    {
        $settings = app('site_global_settings');

        $verify_response = do_license_verify(
            SettingLicense::LICENSE_API_HOST.SettingLicense::LICENSE_API_ROUTE_REVOKE,
            $settings->settingLicense->settings_license_purchase_code,
            $settings->settingLicense->settings_license_codecanyon_username,
            7,
            $domain_host);

        $verify_response_status = $verify_response['status'];
        $verify_response_message = $verify_response['message'];
        $verify_response_body = $verify_response['body'];

        if($verify_response_status)
        {
            if($verify_response_body->status_code == \App\SettingLicense::LICENSE_API_STATUS_CODE_SUCCESS)
            {
                \Session::flash('flash_message', $verify_response_body->status_message);
                \Session::flash('flash_type', 'success');
            }
            else
            {
                \Session::flash('flash_message', $verify_response_body->status_message);
                \Session::flash('flash_type', 'danger');
            }
        }
        else
        {
            \Session::flash('flash_message', $verify_response_message);
            \Session::flash('flash_type', 'danger');
        }

        return redirect()->route('admin.settings.license.edit');
    }

    public function domainVerifyLicenseSetting(Request $request)
    {
        $domain_verify_hash = $request->domain_verify_hash;

        $settings = app('site_global_settings');
        $setting_license = $settings->settingLicense()->first();

        $to_domain_verify_hash = sha1($setting_license->settings_license_purchase_code . strtolower($setting_license->settings_license_codecanyon_username) . $setting_license->settings_license_domain_verify_token);

        if($domain_verify_hash == $to_domain_verify_hash)
        {
            $status_code = SettingLicense::LICENSE_API_STATUS_CODE_SUCCESS;
        }
        else
        {
            $status_code = SettingLicense::LICENSE_API_STATUS_CODE_ERROR;
        }

        return response()->json(array(
            'status_code' => $status_code,
        ));
    }
}

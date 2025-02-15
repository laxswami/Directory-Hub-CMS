<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    const ABOUT_PAGE_ENABLED = 1;
    const ABOUT_PAGE_DISABLED = 0;

    const TERM_PAGE_ENABLED = 1;
    const TERM_PAGE_DISABLED = 0;

    const PRIVACY_PAGE_ENABLED = 1;
    const PRIVACY_PAGE_DISABLED = 0;

    const TRACKING_ON = 1;
    const TRACKING_OFF = 0;
    const NOT_TRACKING_ADMIN = 1;
    const TRACKING_ADMIN = 0;

    const LANGUAGE_EN = 'en';

    const LANGUAGES = array(
        'af' => 'setting_language_af_language',
        'sq' => 'setting_language_sq_language',
        'ar' => 'setting_language_ar_language',
        'hy' => 'setting_language_hy_language',
        'az' => 'setting_language_az_language',
        'be' => 'setting_language_be_language',
        'bn' => 'setting_language_bn_language',
        'bs' => 'setting_language_bs_language',
        'bg' => 'setting_language_bg_language',
        'ca' => 'setting_language_ca_language',
        'zh-CN' => 'setting_language_zh_cn_language',
        'zh-TW' => 'setting_language_zh_tw_language',
        'hr' => 'setting_language_hr_language',
        'cs' => 'setting_language_cs_language',
        'da' => 'setting_language_da_language',
        'nl' => 'setting_language_nl_language',
        'en' => 'setting_language_en_language',
        'et' => 'setting_language_et_language',
        'fi' => 'setting_language_fi_language',
        'fr' => 'setting_language_fr_language',
        'gl' => 'setting_language_gl_language',
        'ka' => 'setting_language_ka_language',
        'de' => 'setting_language_de_language',
        'el' => 'setting_language_el_language',
        'ht' => 'setting_language_ht_language',
        'he' => 'setting_language_he_language',
        'hi' => 'setting_language_hi_language',
        'hu' => 'setting_language_hu_language',
        'is' => 'setting_language_is_language',
        'id' => 'setting_language_id_language',
        'ga' => 'setting_language_ga_language',
        'it' => 'setting_language_it_language',
        'ja' => 'setting_language_ja_language',
        'ko' => 'setting_language_ko_language',
        'ky' => 'setting_language_ky_language',
        'lv' => 'setting_language_lv_language',
        'lt' => 'setting_language_lt_language',
        'lb' => 'setting_language_lb_language',
        'mk' => 'setting_language_mk_language',
        'ms' => 'setting_language_ms_language',
        'mn' => 'setting_language_mn_language',
        'my' => 'setting_language_my_language',
        'ne' => 'setting_language_ne_language',
        'no' => 'setting_language_no_language',
        'fa' => 'setting_language_fa_language',
        'pl' => 'setting_language_pl_language',
        'pt-br' => 'setting_language_pt_br_language',
        'ro' => 'setting_language_ro_language',
        'ru' => 'setting_language_ru_language',
        'sr' => 'setting_language_sr_language',
        'sk' => 'setting_language_sk_language',
        'sl' => 'setting_language_sl_language',
        'so' => 'setting_language_so_language',
        'es' => 'setting_language_es_language',
        'su' => 'setting_language_su_language',
        'sv' => 'setting_language_sv_language',
        'th' => 'setting_language_th_language',
        'tr' => 'setting_language_tr_language',
        'tk' => 'setting_language_tk_language',
        'uk' => 'setting_language_uk_language',
        'uz' => 'setting_language_uz_language',
        'vi' => 'setting_language_vi_language',
    );

    const SITE_HEADER_ENABLED = 1;
    const SITE_HEADER_DISABLED = 0;

    const SITE_FOOTER_ENABLED = 1;
    const SITE_FOOTER_DISABLED = 0;

    const SITE_SMTP_ENABLED = 1;
    const SITE_SMTP_DISABLED = 0;
    const SITE_SMTP_ENCRYPTION_NULL = 0;
    const SITE_SMTP_ENCRYPTION_SSL = 1;
    const SITE_SMTP_ENCRYPTION_TLS = 2;

    const SITE_SMTP_ENCRYPTION_SSL_STR = "ssl";
    const SITE_SMTP_ENCRYPTION_TLS_STR = "tls";

    const SITE_LANG_SOURCE_FILE = 'file';
    const SITE_LANG_SOURCE_DATABASE = 'database';

    const SITE_PAYMENT_PAYPAL_ENABLE = 1;
    const SITE_PAYMENT_PAYPAL_DISABLE = 0;
    const SITE_PAYMENT_RAZORPAY_ENABLE = 1;
    const SITE_PAYMENT_RAZORPAY_DISABLE = 0;
    const SITE_PAYMENT_STRIPE_ENABLE = 1;
    const SITE_PAYMENT_STRIPE_DISABLE = 0;
    const SITE_PAYMENT_BANK_TRANSFER_ENABLE = 1;
    const SITE_PAYMENT_BANK_TRANSFER_DISABLE = 0;

    const SITE_PAYMENT_PAYPAL_SANDBOX = 'sandbox';
    const SITE_PAYMENT_PAYPAL_LIVE = 'live';

    const SITE_PAYMENT_PAYUMONEY_ENABLE = 1;
    const SITE_PAYMENT_PAYUMONEY_DISABLE = 0;
    const SITE_PAYMENT_PAYUMONEY_MODE_LIVE = 'live';
    const SITE_PAYMENT_PAYUMONEY_MODE_TEST = 'test';

    const SITE_RECAPTCHA_LOGIN_ENABLE = 1;
    const SITE_RECAPTCHA_LOGIN_DISABLE = 0;

    const SITE_RECAPTCHA_SIGN_UP_ENABLE = 1;
    const SITE_RECAPTCHA_SIGN_UP_DISABLE = 0;

    const SITE_RECAPTCHA_CONTACT_ENABLE = 1;
    const SITE_RECAPTCHA_CONTACT_DISABLE = 0;

    const SITE_RECAPTCHA_ITEM_LEAD_ENABLE = 1;
    const SITE_RECAPTCHA_ITEM_LEAD_DISABLE = 0;

    const SITE_SITEMAP_INDEX_ENABLE = 1;
    const SITE_SITEMAP_INDEX_DISABLE = 0;

    const SITE_SITEMAP_PAGE_ENABLE = 1;
    const SITE_SITEMAP_PAGE_DISABLE = 0;

    const SITE_SITEMAP_CATEGORY_ENABLE = 1;
    const SITE_SITEMAP_CATEGORY_DISABLE = 0;

    const SITE_SITEMAP_LISTING_ENABLE = 1;
    const SITE_SITEMAP_LISTING_DISABLE = 0;

    const SITE_SITEMAP_POST_ENABLE = 1;
    const SITE_SITEMAP_POST_DISABLE = 0;

    const SITE_SITEMAP_TAG_ENABLE = 1;
    const SITE_SITEMAP_TAG_DISABLE = 0;

    const SITE_SITEMAP_TOPIC_ENABLE = 1;
    const SITE_SITEMAP_TOPIC_DISABLE = 0;

    const SITE_SITEMAP_STATE_ENABLE = 1;
    const SITE_SITEMAP_STATE_DISABLE = 0;

    const SITE_SITEMAP_CITY_ENABLE = 1;
    const SITE_SITEMAP_CITY_DISABLE = 0;

    const SITE_SITEMAP_INCLUDE_TO_INDEX = 1;
    const SITE_SITEMAP_NOT_INCLUDE_TO_INDEX = 0;

    const SITE_SITEMAP_FREQUENCY_ALWAYS = 'always';
    const SITE_SITEMAP_FREQUENCY_HOURLY = 'hourly';
    const SITE_SITEMAP_FREQUENCY_DAILY = 'daily';
    const SITE_SITEMAP_FREQUENCY_WEEKLY = 'weekly';
    const SITE_SITEMAP_FREQUENCY_MONTHLY = 'monthly';
    const SITE_SITEMAP_FREQUENCY_YEARLY = 'yearly';
    const SITE_SITEMAP_FREQUENCY_NEVER = 'never';

    const SITE_SITEMAP_FORMAT_XML = 'xml';
    const SITE_SITEMAP_FORMAT_HTML = 'html';
    const SITE_SITEMAP_FORMAT_TXT = 'txt';
    const SITE_SITEMAP_FORMAT_ROR_RSS = 'ror-rss';
    const SITE_SITEMAP_FORMAT_ROR_RDF = 'ror-rdf';

    const SITE_SITEMAP_SHOW_IN_FOOTER = 1;
    const SITE_SITEMAP_NOT_SHOW_IN_FOOTER = 0;

    const SITE_SITEMAP_MAXIMUM_URLS = 5000;

    const SITE_PRODUCT_AUTO_APPROVAL_ENABLED = 1;
    const SITE_PRODUCT_AUTO_APPROVAL_DISABLED = 0;

    const SITE_MAP_OPEN_STREET_MAP = 1;
    const SITE_MAP_GOOGLE_MAP = 2;

    const SITE_MAP_RELOAD_HOMEPAGE_YES = 1;
    const SITE_MAP_RELOAD_HOMEPAGE_NO = 2;

    const SITE_MAINTENANCE_MODE_ON = 2;
    const SITE_MAINTENANCE_MODE_OFF = 1;

    const DEMO_ADMIN_LOGIN_EMAIL = 'admin@mail.com';
    const DEMO_ADMIN_LOGIN_PASSWORD = '12345678';

    const DEMO_USER_LOGIN_EMAIL = 'kennedi.yuic@yahoo.com';
    const DEMO_USER_LOGIN_PASSWORD = '12345678';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setting_site_logo', 'setting_site_favicon', 'setting_site_name', 'setting_site_address', 'setting_site_state',
        'setting_site_city', 'setting_site_country', 'setting_site_postal_code', 'setting_site_email', 'setting_site_phone',
        'setting_site_about', 'setting_page_about_enable', 'setting_page_about', 'setting_site_location_lat',
        'setting_site_location_lng', 'setting_site_location_country_id', 'setting_site_seo_home_title', 'setting_site_seo_home_description',
        'setting_site_seo_home_keywords', 'setting_page_terms_of_service_enable', 'setting_page_terms_of_service', 'setting_page_privacy_policy_enable',
        'setting_page_privacy_policy', 'setting_site_google_analytic_enabled', 'setting_site_google_analytic_tracking_id',
        'setting_site_google_analytic_not_track_admin', 'setting_site_header_enabled', 'setting_site_header', 'setting_site_footer_enabled',
        'setting_site_footer', 'settings_site_smtp_enabled', 'settings_site_smtp_sender_name', 'settings_site_smtp_sender_email',
        'settings_site_smtp_host', 'settings_site_smtp_port', 'settings_site_smtp_encryption', 'settings_site_smtp_username',
        'settings_site_smtp_password', 'setting_site_language', 'setting_site_paypal_mode', 'setting_site_paypal_payment_action',
        'setting_site_paypal_currency', 'setting_site_paypal_billing_type', 'setting_site_paypal_notify_url', 'setting_site_paypal_locale',
        'setting_site_paypal_validate_ssl', 'setting_site_paypal_sandbox_username', 'setting_site_paypal_sandbox_password',
        'setting_site_paypal_sandbox_secret', 'setting_site_paypal_sandbox_certificate', 'setting_site_paypal_sandbox_app_id',
        'setting_site_paypal_live_username', 'setting_site_paypal_live_password', 'setting_site_paypal_live_secret', 'setting_site_paypal_live_certificate',
        'setting_site_paypal_live_app_id', 'setting_site_razorpay_enable', 'setting_site_razorpay_api_key', 'setting_site_razorpay_api_secret',
        'setting_site_razorpay_currency', 'setting_site_paypal_enable', 'setting_site_sitemap_index_enable', 'setting_site_sitemap_page_enable',
        'setting_site_sitemap_category_enable', 'setting_site_sitemap_listing_enable', 'setting_site_sitemap_post_enable',
        'setting_site_sitemap_tag_enable', 'setting_site_sitemap_page_frequency', 'setting_site_sitemap_category_frequency',
        'setting_site_sitemap_listing_frequency', 'setting_site_sitemap_post_frequency', 'setting_site_sitemap_tag_frequency',
        'setting_site_sitemap_topic_frequency', 'setting_site_sitemap_page_format', 'setting_site_sitemap_page_include_to_index',
        'setting_site_sitemap_category_format', 'setting_site_sitemap_category_include_to_index', 'setting_site_sitemap_listing_format',
        'setting_site_sitemap_listing_include_to_index', 'setting_site_sitemap_post_format', 'setting_site_sitemap_post_include_to_index',
        'setting_site_sitemap_tag_format', 'setting_site_sitemap_tag_include_to_index', 'setting_site_sitemap_topic_format',
        'setting_site_sitemap_topic_include_to_index', 'setting_product_max_gallery_photos', 'setting_product_auto_approval_enable',
        'setting_site_map', 'setting_site_map_google_api_key', 'setting_site_recaptcha_login_enable', 'setting_site_recaptcha_sign_up_enable',
        'setting_site_recaptcha_contact_enable', 'setting_site_recaptcha_site_key', 'setting_site_recaptcha_secret_key',
        'setting_site_stripe_enable', 'setting_site_stripe_publishable_key', 'setting_site_stripe_secret_key', 'setting_site_stripe_webhook_signing_secret',
        'setting_site_stripe_currency', 'setting_site_sitemap_show_in_footer', 'setting_site_sitemap_topic_enable',
        'setting_product_currency_symbol', 'setting_site_last_cached_at', 'setting_site_payumoney_enable', 'setting_site_payumoney_mode',
        'setting_site_payumoney_merchant_key', 'setting_site_payumoney_salt', 'setting_site_sitemap_state_enable',
        'setting_site_sitemap_state_frequency', 'setting_site_sitemap_state_format', 'setting_site_sitemap_state_include_to_index',
        'setting_site_sitemap_city_enable', 'setting_site_sitemap_city_frequency', 'setting_site_sitemap_city_format',
        'setting_site_sitemap_city_include_to_index', 'setting_site_maintenance_mode', 'setting_site_recaptcha_item_lead_enable',
        'setting_site_map_reload_homepage',
    ];

    public function settingLicense()
    {
        return $this->hasOne('App\SettingLicense');
    }

    public function settingItem()
    {
        return $this->hasOne('App\SettingItem');
    }

    public function settingLanguage()
    {
        return $this->hasOne('App\SettingLanguage');
    }

    public function deleteLogoImage()
    {
        if(!empty($this->setting_site_logo))
        {
            if(Storage::disk('public')->exists('setting/' . $this->setting_site_logo)){
                Storage::disk('public')->delete('setting/' . $this->setting_site_logo);
            }

            $this->setting_site_logo = null;
            $this->save();
        }
    }

    public function deleteFaviconImage()
    {
        if(!empty($this->setting_site_favicon))
        {
            if(Storage::disk('public')->exists('setting/' . $this->setting_site_favicon)){
                Storage::disk('public')->delete('setting/' . $this->setting_site_favicon);
            }

            $this->setting_site_favicon = null;
            $this->save();
        }
    }
}

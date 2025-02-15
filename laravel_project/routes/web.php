<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Start utils routes
 */
Route::get('/utils/link', 'UtilsController@makeSymlinks')->name('utils.link');
Route::get('/utils/cache', 'UtilsController@makeCache')->name('utils.cache');
/**
 * End utils routes
 */

/**
 * Start website routes
 */
Route::middleware(['installed','demo','global_variables','maintenance'])->group(function () {

    /**
     * Auth routes
     */
    Auth::routes(['verify' => true]);

    /**
     * Social login routes
     */

    // facebook
    Route::get('/auth/facebook', 'Auth\LoginController@redirectToFacebook')->name('auth.login.facebook');
    Route::get('/auth/facebook/callback', 'Auth\LoginController@handleFacebookCallback')->name('auth.login.facebook.callback');

    // google
    Route::get('/auth/google', 'Auth\LoginController@redirectToGoogle')->name('auth.login.google');
    Route::get('/auth/google/callback', 'Auth\LoginController@handleGoogleCallback')->name('auth.login.google.callback');

    // twitter
    Route::get('/auth/twitter', 'Auth\LoginController@redirectToTwitter')->name('auth.login.twitter');
    Route::get('/auth/twitter/callback', 'Auth\LoginController@handleTwitterCallback')->name('auth.login.twitter.callback');

    // linkedin
    Route::get('/auth/linkedin', 'Auth\LoginController@redirectToLinkedIn')->name('auth.login.linkedin');
    Route::get('/auth/linkedin/callback', 'Auth\LoginController@handleLinkedInCallback')->name('auth.login.linkedin.callback');

    // github
    Route::get('/auth/github', 'Auth\LoginController@redirectToGitHub')->name('auth.login.github');
    Route::get('/auth/github/callback', 'Auth\LoginController@handleGitHubCallback')->name('auth.login.github.callback');

    /**
     * Public routes
     */
    Route::get('/', 'PagesController@index')->name('page.home');

    Route::get('/search', 'PagesController@search')->name('page.search');

    Route::get('/about', 'PagesController@about')->name('page.about');
    Route::get('/contact', 'PagesController@contact')->name('page.contact');
    Route::post('/contact', 'PagesController@doContact')->name('page.contact.do');

    Route::get('/categories', 'PagesController@categories')->name('page.categories');
    Route::get('/category/{category_slug}', 'PagesController@category')->name('page.category');
    Route::get('/category/{category_slug}/state/{state_slug}', 'PagesController@categoryByState')->name('page.category.state');
    Route::get('/category/{category_slug}/state/{state_slug}/city/{city_slug}', 'PagesController@categoryByStateCity')->name('page.category.state.city');

    Route::get('/state/{state_slug}', 'PagesController@state')->name('page.state');
    Route::get('/state/{state_slug}/city/{city_slug}', 'PagesController@city')->name('page.city');

    Route::get('/listing/{item_slug}', 'PagesController@item')->name('page.item');
    Route::get('/listing/{item_slug}/product/{product_slug}', 'PagesController@product')->name('page.product');

    Route::middleware(['auth'])->group(function () {

        Route::post('/items/{item_slug}/email', 'PagesController@emailItem')->name('page.item.email');
        Route::post('/items/{item_slug}/save', 'PagesController@saveItem')->name('page.item.save');
        Route::post('/items/{item_slug}/unsave', 'PagesController@unSaveItem')->name('page.item.unsave');
    });

    Route::post('/items/{item_slug}/lead/store', 'PagesController@storeItemLead')->name('page.item.lead.store');

    Route::get('/pricing', 'PagesController@pricing')->name('page.pricing');
    Route::get('/terms-of-service', 'PagesController@termsOfService')->name('page.terms-of-service');
    Route::get('/privacy-policy', 'PagesController@privacyPolicy')->name('page.privacy-policy');

    /**
     * Blog routes
     */
    Route::group(['prefix'=>'blog'], function(){

        // Get all published posts
        Route::get('/', 'PagesController@blog')->name('page.blog');

        // Get posts for a given tag
        Route::get('/tag/{tag_slug}', 'PagesController@blogByTag')->name('page.blog.tag');

        // Get posts for a given topic
        Route::get('/topic/{topic_slug}', 'PagesController@blogByTopic')->name('page.blog.topic');

        // Find a single post
        Route::get('/{blog_slug}', 'PagesController@blogPost')
            ->middleware('Canvas\Http\Middleware\Session')
            ->name('page.blog.show');

    });

    Route::get('/locale/update/{user_prefer_language}', 'PagesController@updateLocale')->name('page.locale.update');
    Route::get('/country/update/{user_prefer_country_id}', 'PagesController@updateCountry')->name('page.country.update');

    /**
     * Start payment gateway webhooks
     */
    // PayPal IPN (Webhook) Route
    Route::post('/paypal/notify', 'User\PaypalController@notify')->name('user.paypal.notify');

    // Razorpay Webhook Route
    Route::post('/razorpay/notify', 'User\RazorpayController@notify')->name('user.razorpay.notify');

    // Stripe webhook route
    Route::post('/stripe/notify', 'User\StripeController@notify')->name('user.stripe.notify');

    // Instamojo Webhook Route
    //Route::post('/instamojo/notify', 'User\InstamojoController@notify')->name('user.instamojo.notify');
    /**
     * End payment gateway webhooks
     */

    /**
     * Start license domain verify
     */
    Route::post('/domain-verify', 'Admin\SettingController@domainVerifyLicenseSetting')->name('admin.settings.license.domain.verify');
    /**
     * End license domain verify
     */

    /**
     * Start site map routes
     */
    Route::get('/sitemap', 'SitemapController@index')->name('page.sitemap.index');
    Route::get('/sitemap/page', 'SitemapController@page')->name('page.sitemap.page');
    Route::get('/sitemap/category', 'SitemapController@category')->name('page.sitemap.category');
    Route::get('/sitemap/listing', 'SitemapController@listing')->name('page.sitemap.listing');
    Route::get('/sitemap/listing/{page_number}', 'SitemapController@listingPagination')->name('page.sitemap.listing.pagination');
    Route::get('/sitemap/post', 'SitemapController@post')->name('page.sitemap.post');
    Route::get('/sitemap/tag', 'SitemapController@tag')->name('page.sitemap.tag');
    Route::get('/sitemap/topic', 'SitemapController@topic')->name('page.sitemap.topic');
    Route::get('/sitemap/state', 'SitemapController@state')->name('page.sitemap.state');
    Route::get('/sitemap/state/{page_number}', 'SitemapController@statePagination')->name('page.sitemap.state.pagination');
    Route::get('/sitemap/city', 'SitemapController@city')->name('page.sitemap.city');
    Route::get('/sitemap/city/{page_number}', 'SitemapController@cityPagination')->name('page.sitemap.city.pagination');

    /**
     * End site map routes
     */


    /**
     * ajax routes serve frontend elements
     */
    Route::get('/ajax/cities/{state_id}', 'PagesController@jsonGetCitiesByState')->name('json.city');
    Route::get('/ajax/states/{country_id}', 'PagesController@jsonGetStatesByCountry')->name('json.state');

    Route::post('/ajax/item/image/delete/{item_id}', 'PagesController@jsonDeleteItemFeatureImage')->name('json.item.image.feature');
    Route::post('/ajax/item/gallery/delete/{item_image_gallery_id}', 'PagesController@jsonDeleteItemImageGallery')->name('json.item.image.gallery');
    Route::post('/ajax/item/review/gallery/delete/{review_image_gallery_id}', 'PagesController@jsonDeleteReviewImageGallery')->name('json.review.image.gallery');

    Route::post('/ajax/location/save/{lat}/{lng}', 'PagesController@ajaxLocationSave')->name('ajax.location.save');

    Route::post('/ajax/product/image/delete/{product_id}', 'PagesController@jsonDeleteProductFeatureImage')->name('json.product.image.feature');
    Route::post('/ajax/product/gallery/delete/{product_image_gallery_id}', 'PagesController@jsonDeleteProductImageGallery')->name('json.product.image.gallery');

    Route::post('/ajax/user/image/delete/{user_id}', 'PagesController@jsonDeleteUserProfileImage')->name('json.user.image.profile');

    Route::post('/ajax/setting/logo/delete', 'PagesController@jsonDeleteSettingLogoImage')->name('json.setting.logo');
    Route::post('/ajax/setting/favicon/delete', 'PagesController@jsonDeleteSettingFaviconImage')->name('json.setting.favicon');

    Route::post('/ajax/category/image/delete/{category_id}', 'PagesController@jsonDeleteCategoryImage')->name('json.category.image.delete');
    /**
     * Back-end admin routes
     */
    Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>['verified','auth','admin'],'as'=>'admin.'], function(){

        Route::get('/dashboard','PagesController@index')->name('index');
        Route::resource('/countries', 'CountryController');
        Route::resource('/states', 'StateController');
        Route::resource('/cities', 'CityController');
        Route::resource('/categories', 'CategoryController');
        Route::resource('/custom-fields', 'CustomFieldController');
        Route::resource('/items', 'ItemController');

        // item slug update route
        Route::put('/items/{item}/slug/update', 'ItemController@updateItemSlug')->name('item.slug.update');

        // item section & collection routes for admin
        Route::get('/items/{item}/sections/index', 'ItemController@indexItemSections')->name('items.sections.index');
        Route::post('/items/{item}/sections/store', 'ItemController@storeItemSection')->name('items.sections.store');
        Route::get('/items/{item}/sections/{item_section}/edit', 'ItemController@editItemSection')->name('items.sections.edit');
        Route::put('/items/{item}/sections/{item_section}/update', 'ItemController@updateItemSection')->name('items.sections.update');
        Route::delete('/items/{item}/sections/{item_section}/destroy', 'ItemController@destroyItemSection')->name('items.sections.destroy');

        Route::put('/items/{item}/sections/{item_section}/rank-up', 'ItemController@rankUpItemSection')->name('items.sections.rank.up');
        Route::put('/items/{item}/sections/{item_section}/rank-down', 'ItemController@rankDownItemSection')->name('items.sections.rank.down');

        Route::post('/items/{item}/sections/{item_section}/collections/store', 'ItemController@storeItemSectionCollections')->name('items.sections.collections.store');
        Route::put('/items/{item}/sections/{item_section}/collections/{item_section_collection}/rank-up', 'ItemController@rankUpItemSectionCollection')->name('items.sections.collections.rank.up');
        Route::put('/items/{item}/sections/{item_section}/collections/{item_section_collection}/rank-down', 'ItemController@rankDownItemSectionCollection')->name('items.sections.collections.rank.down');
        Route::delete('/items/{item}/sections/{item_section}/collections/{item_section_collection}/destroy', 'ItemController@destroyItemSectionCollection')->name('items.sections.collections.destroy');

        // item claims routes for admin
        Route::resource('/item-claims', 'ItemClaimController');
        Route::post('/item-claims/download/{item_claim}', 'ItemClaimController@downloadItemClaimDoc')->name('item-claims.download.do');
        Route::post('/item-claims/{item_claim}/approve', 'ItemClaimController@approveItemClaim')->name('item-claims.approve.do');
        Route::post('/item-claims/{item_claim}/disapprove', 'ItemClaimController@disapproveItemClaim')->name('item-claims.disapprove.do');

        Route::put('/items/{item}/category/update', 'ItemController@updateItemCategory')->name('item.category.update');

        Route::get('/items/saved/index', 'ItemController@savedItems')->name('items.saved');
        Route::post('/items/{item_slug}/unsave', 'ItemController@unSaveItem')->name('items.unsave');

        Route::put('/items/{item}/approve', 'ItemController@approveItem')->name('items.approve');
        Route::put('/items/{item}/disapprove', 'ItemController@disApproveItem')->name('items.disapprove');
        Route::put('/items/{item}/suspend', 'ItemController@suspendItem')->name('items.suspend');

        Route::post('/items/bulk/approve', 'ItemController@bulkApproveItem')->name('items.bulk.approve');
        Route::post('/items/bulk/disapprove', 'ItemController@bulkDisapproveItem')->name('items.bulk.disapprove');
        Route::post('/items/bulk/suspend', 'ItemController@bulkSuspendItem')->name('items.bulk.suspend');
        Route::post('/items/bulk/delete', 'ItemController@bulkDeleteItem')->name('items.bulk.delete');

        // item reviews routes
        Route::get('/items/{item_slug}/reviews/create', 'ItemController@itemReviewsCreate')->name('items.reviews.create');
        Route::post('/items/{item_slug}/reviews/store', 'ItemController@itemReviewsStore')->name('items.reviews.store');
        Route::get('/items/{item_slug}/reviews/{review}/edit', 'ItemController@itemReviewsEdit')->name('items.reviews.edit');
        Route::put('/items/{item_slug}/reviews/update/{review}', 'ItemController@itemReviewsUpdate')->name('items.reviews.update');
        Route::delete('/items/{item_slug}/reviews/destroy/{review}', 'ItemController@itemReviewsDestroy')->name('items.reviews.destroy');

        // item reviews management admin routes
        Route::get('/items/reviews/index', 'ItemController@itemReviewsIndex')->name('items.reviews.index');
        Route::get('/items/reviews/{review_id}', 'ItemController@itemReviewsShow')->name('items.reviews.show');
        Route::put('/items/reviews/update/{review_id}/approve', 'ItemController@itemReviewsApprove')->name('items.reviews.approve');
        Route::put('/items/reviews/update/{review_id}/disapprove', 'ItemController@itemReviewsDisapprove')->name('items.reviews.disapprove');
        Route::delete('/items/reviews/destroy/{review_id}', 'ItemController@itemReviewsDelete')->name('items.reviews.delete');

        // item hours routes
        Route::put('/items/hours/update/{item_hour}', 'ItemController@updateItemHour')->name('items.hours.update');
        Route::delete('/items/hours/destroy/{item_hour}', 'ItemController@destroyItemHour')->name('items.hours.destroy');

        // item hour exceptions routes
        Route::put('/items/hour-exceptions/update/{item_hour_exception}', 'ItemController@updateItemHourException')->name('items.hour-exceptions.update');
        Route::delete('/items/hour-exceptions/destroy/{item_hour_exception}', 'ItemController@destroyItemHourException')->name('items.hour-exceptions.destroy');

        // message routes
        Route::resource('/messages', 'MessageController');

        // plan routes
        Route::resource('/plans', 'PlanController');

        // subscription routes
        Route::resource('/subscriptions', 'SubscriptionController');

        // product routes
        Route::resource('/products', 'ProductController');
        Route::put('/products/{product}/attribute/update', 'ProductController@updateProductAttribute')->name('product.attribute.update');

        Route::put('/products/{product}/approve', 'ProductController@approveProduct')->name('product.approve');
        Route::put('/products/{product}/disapprove', 'ProductController@disapproveProduct')->name('product.disapprove');
        Route::put('/products/{product}/suspend', 'ProductController@suspendProduct')->name('product.suspend');

        Route::put('/products/{product}/feature/{product_feature}/rank-up', 'ProductController@rankUpProductFeature')->name('product.feature.up');
        Route::put('/products/{product}/feature/{product_feature}/rank-down', 'ProductController@rankDownProductFeature')->name('product.feature.down');
        Route::delete('/products/{product}/feature/{product_feature}/destroy', 'ProductController@destroyProductFeature')->name('product.feature.destroy');

        // product attribute routes
        Route::resource('/attributes', 'AttributeController');

        Route::resource('/users', 'UserController');
        Route::get('/users/password/{user}/edit', 'UserController@editUserPassword')->name('users.password.edit');
        Route::post('/users/password/{user}', 'UserController@updateUserPassword')->name('users.password.update');

        Route::put('/users/{user}/suspend', 'UserController@suspendUser')->name('users.suspend');
        Route::put('/users/{user}/unsuspend', 'UserController@unsuspendUser')->name('users.unsuspend');

        Route::post('/users/bulk/verify', 'UserController@bulkVerifyUser')->name('users.bulk.verify');
        Route::post('/users/bulk/suspend', 'UserController@bulkSuspendUser')->name('users.bulk.suspend');
        Route::post('/users/bulk/unsuspend', 'UserController@bulkUnsuspendUser')->name('users.bulk.unsuspend');
        Route::post('/users/bulk/delete', 'UserController@bulkDeleteUser')->name('users.bulk.delete');

        Route::get('/profile', 'UserController@editProfile')->name('users.profile.edit');
        Route::post('/profile', 'UserController@updateProfile')->name('users.profile.update');
        Route::get('/profile/password', 'UserController@editProfilePassword')->name('users.profile.password.edit');
        Route::post('/profile/password', 'UserController@updateProfilePassword')->name('users.profile.password.update');

        Route::resource('/testimonials', 'TestimonialController');
        Route::resource('/faqs', 'FaqController');
        Route::resource('/social-medias', 'SocialMediaController');

        // setting general
        Route::get('/settings/general', 'SettingController@editGeneralSetting')->name('settings.general.edit');
        Route::post('/settings/general', 'SettingController@updateGeneralSetting')->name('settings.general.update');

        Route::post('/settings/general/smtp/test', 'SettingController@testSmtpSetting')->name('settings.general.smtp.test');

        // setting about page
        Route::get('/settings/about', 'SettingController@editAboutPageSetting')->name('settings.page.about.edit');
        Route::post('/settings/about', 'SettingController@updateAboutPageSetting')->name('settings.page.about.update');

        // setting terms-of-service page
        Route::get('/settings/terms-of-service', 'SettingController@editTermsOfServicePageSetting')->name('settings.page.terms-service.edit');
        Route::post('/settings/terms-of-service', 'SettingController@updateTermsOfServicePageSetting')->name('settings.page.terms-service.update');

        // setting privacy-policy page
        Route::get('/settings/privacy-policy', 'SettingController@editPrivacyPolicyPageSetting')->name('settings.page.privacy-policy.edit');
        Route::post('/settings/privacy-policy', 'SettingController@updatePrivacyPolicyPageSetting')->name('settings.page.privacy-policy.update');

        // setting google recaptcha v2
        Route::get('/settings/recaptcha', 'SettingController@editRecaptchaSetting')->name('settings.recaptcha.edit');
        Route::post('/settings/recaptcha', 'SettingController@updateRecaptchaSetting')->name('settings.recaptcha.update');

        // setting site map
        Route::get('/settings/sitemap', 'SettingController@editSitemapSetting')->name('settings.sitemap.edit');
        Route::post('/settings/sitemap', 'SettingController@updateSitemapSetting')->name('settings.sitemap.update');

        // setting website cache
        Route::get('/settings/cache', 'SettingController@editCacheSetting')->name('settings.cache.edit');
        Route::post('/settings/cache', 'SettingController@updateCacheSetting')->name('settings.cache.update');
        Route::delete('/settings/cache/destroy', 'SettingController@deleteCacheSetting')->name('settings.cache.destroy');

        // setting session
        Route::get('/settings/session', 'SettingController@editSessionSetting')->name('settings.session.edit');
        Route::post('/settings/session', 'SettingController@updateSessionSetting')->name('settings.session.update');

        // setting language
        Route::get('/settings/language', 'SettingController@editLanguageSetting')->name('settings.language.edit');
        Route::post('/settings/language', 'SettingController@updateLanguageSetting')->name('settings.language.update');

        // setting product
        Route::get('/settings/product', 'SettingController@editProductSetting')->name('settings.product.edit');
        Route::post('/settings/product', 'SettingController@updateProductSetting')->name('settings.product.update');

        // setting item
        Route::get('/settings/item', 'SettingController@editItemSetting')->name('settings.item.edit');
        Route::post('/settings/item', 'SettingController@updateItemSetting')->name('settings.item.update');

        // maintenance mode
        Route::get('/settings/maintenance', 'SettingController@editMaintenanceSetting')->name('settings.maintenance.edit');
        Route::post('/settings/maintenance', 'SettingController@updateMaintenanceSetting')->name('settings.maintenance.update');

        // setting license
        Route::get('/settings/license', 'SettingController@editLicenseSetting')->name('settings.license.edit');
        Route::post('/settings/license', 'SettingController@updateLicenseSetting')->name('settings.license.update');
        Route::post('/settings/license/revoke/{domain_host}', 'SettingController@revokeDomainLicenseSetting')->name('settings.license.revoke');
        Route::post('/settings/license/domain-verify-token', 'SettingController@updateDomainVerifyTokenLicenseSetting')->name('settings.license.domain-verify-token.update');

        /**
         * Start payment gateway settings
         */
        // bank transfer setting
        Route::get('/settings/payment/bank-transfer', 'SettingController@indexBankTransferPaymentSetting')->name('settings.payment.bank-transfer.index');
        Route::get('/settings/payment/bank-transfer/create', 'SettingController@createBankTransferPaymentSetting')->name('settings.payment.bank-transfer.create');
        Route::post('/settings/payment/bank-transfer/store', 'SettingController@storeBankTransferPaymentSetting')->name('settings.payment.bank-transfer.store');
        Route::get('/settings/payment/bank-transfer/{setting_bank_transfer}/edit', 'SettingController@editBankTransferPaymentSetting')->name('settings.payment.bank-transfer.edit');
        Route::put('/settings/payment/bank-transfer/{setting_bank_transfer}', 'SettingController@updateBankTransferPaymentSetting')->name('settings.payment.bank-transfer.update');
        Route::delete('/settings/payment/bank-transfer/{setting_bank_transfer}', 'SettingController@destroyBankTransferPaymentSetting')->name('settings.payment.bank-transfer.destroy');

        Route::get('/settings/payment/bank-transfer/pending', 'SettingController@indexPendingBankTransferPayment')->name('settings.payment.bank-transfer.pending.index');
        Route::get('/settings/payment/bank-transfer/pending/{invoice}', 'SettingController@showPendingBankTransferPayment')->name('settings.payment.bank-transfer.pending.show');
        Route::post('/settings/payment/bank-transfer/pending/{invoice}/approve', 'SettingController@approveBankTransferPayment')->name('settings.payment.bank-transfer.pending.approve');
        Route::post('/settings/payment/bank-transfer/pending/{invoice}/reject', 'SettingController@rejectBankTransferPayment')->name('settings.payment.bank-transfer.pending.reject');

        // paypal setting
        Route::get('/settings/payment/paypal', 'SettingController@editPayPalPaymentSetting')->name('settings.payment.paypal.edit');
        Route::post('/settings/payment/paypal', 'SettingController@updatePayPalPaymentSetting')->name('settings.payment.paypal.update');

        // razorpay setting
        Route::get('/settings/payment/razorpay', 'SettingController@editRazorpayPaymentSetting')->name('settings.payment.razorpay.edit');
        Route::post('/settings/payment/razorpay', 'SettingController@updateRazorpayPaymentSetting')->name('settings.payment.razorpay.update');

        // stripe setting
        Route::get('/settings/payment/stripe', 'SettingController@editStripePaymentSetting')->name('settings.payment.stripe.edit');
        Route::post('/settings/payment/stripe', 'SettingController@updateStripePaymentSetting')->name('settings.payment.stripe.update');

        // payumoney setting
        Route::get('/settings/payment/payumoney', 'SettingController@editPayumoneyPaymentSetting')->name('settings.payment.payumoney.edit');
        Route::post('/settings/payment/payumoney', 'SettingController@updatePayumoneyPaymentSetting')->name('settings.payment.payumoney.update');
        /**
         * End payment gateway settings
         */

        Route::get('/comments', 'CommentController@index')->name('comments.index');
        Route::put('/comments/{comment}/approve', 'CommentController@approve')->name('comments.approve');
        Route::put('/comments/{comment}/disapprove', 'CommentController@disapprove')->name('comments.disapprove');
        Route::delete('/comments/{comment}/delete', 'CommentController@destroy')->name('comments.destroy');

        // advertisement management
        Route::resource('/advertisements', 'AdvertisementController');

        // social login management
        Route::resource('/social-logins', 'SocialLoginController');

        // language translation management
        Route::get('/lang/sync', 'LangController@syncIndex')->name('lang.sync.index');
        Route::post('/lang/sync/do', 'LangController@syncDo')->name('lang.sync.do');
        Route::post('/lang/sync/restore', 'LangController@syncRestore')->name('lang.sync.restore');

        // customization routes
        Route::get('/customization/color', 'CustomizationController@colorEdit')->name('customization.color.edit');
        Route::post('/customization/color', 'CustomizationController@colorUpdate')->name('customization.color.update');
        Route::post('/customization/color/restore', 'CustomizationController@colorRestore')->name('customization.color.restore');

        Route::get('/customization/header', 'CustomizationController@headerEdit')->name('customization.header.edit');
        Route::post('/customization/header', 'CustomizationController@headerUpdate')->name('customization.header.update');


        /**
         * Start bulk importer routes
         */
        Route::get('/importer/csv/upload', 'ImporterController@showUpload')->name('importer.csv.upload.show');
        Route::post('/importer/csv/upload', 'ImporterController@processUpload')->name('importer.csv.upload.process');

        Route::get('/importer/csv/data', 'ImporterController@indexCsvData')->name('importer.csv.upload.data.index');
        Route::get('/importer/csv/data/{import_csv_data}/edit', 'ImporterController@editCsvData')->name('importer.csv.upload.data.edit');
        Route::post('/importer/csv/data/{import_csv_data}/parse-ajax', 'ImporterController@ajaxParseCsvData')->name('importer.csv.upload.data.parse');
        Route::post('/importer/csv/data/{import_csv_data}/parse/progress-ajax', 'ImporterController@ajaxParseProgressCsvData')->name('importer.csv.upload.data.parse.progress');
        Route::delete('/importer/csv/data/{import_csv_data}/destroy', 'ImporterController@destroyCsvData')->name('importer.csv.upload.data.destroy');

        Route::get('/importer/item/data', 'ImporterController@indexItemData')->name('importer.item.data.index');
        Route::get('/importer/item/data/{import_item_data}/edit', 'ImporterController@editItemData')->name('importer.item.data.edit');
        Route::put('/importer/item/data/{import_item_data}/update', 'ImporterController@updateItemData')->name('importer.item.data.update');
        Route::delete('/importer/item/data/{import_item_data}/destroy', 'ImporterController@destroyItemData')->name('importer.item.data.destroy');
        Route::post('/importer/item/data/{import_item_data}/destroy-ajax', 'ImporterController@ajaxDestroyItemData')->name('importer.item.data.destroy-ajax');
        Route::post('/importer/item/data/{import_item_data}/import', 'ImporterController@importItemData')->name('importer.item.data.import');
        Route::post('/importer/item/data/{import_item_data}/import-ajax', 'ImporterController@ajaxImportItemData')->name('importer.item.data.import-ajax');
        /**
         * End bulk importer routes
         */

        /**
         * Start theme routes
         */
        Route::get('/themes', 'ThemeController@index')->name('themes.index');
        Route::get('/themes/create', 'ThemeController@createTheme')->name('themes.create');
        Route::post('/themes/store', 'ThemeController@storeTheme')->name('themes.store');
        Route::post('/themes/{theme}/active', 'ThemeController@activeTheme')->name('themes.active');
        Route::delete('/themes/{theme}/destroy', 'ThemeController@destroyTheme')->name('themes.destroy');

        Route::get('/themes/{theme}/customization/color/edit', 'ThemeController@editThemeColor')->name('themes.customization.color.edit');
        Route::post('/themes/{theme}/customization/color/update', 'ThemeController@updateThemeColor')->name('themes.customization.color.update');
        Route::post('/themes/{theme}/customization/color/restore', 'ThemeController@restoreThemeColor')->name('themes.customization.color.restore');

        Route::get('/themes/{theme}/customization/header/edit', 'ThemeController@editThemeHeader')->name('themes.customization.header.edit');
        Route::post('/themes/{theme}/customization/header/update', 'ThemeController@updateThemeHeader')->name('themes.customization.header.update');
        Route::post('/themes/{theme}/customization/header/restore', 'ThemeController@restoreThemeHeader')->name('themes.customization.header.restore');
        /**
         * End theme routes
         */

        // item leads routes
        Route::resource('/item-leads', 'ItemLeadController');
    });

    /**
     * Back-end user routes
     */
    Route::group(['prefix'=>'user','namespace'=>'User','middleware'=>['verified','auth','user'],'as'=>'user.'], function(){

        Route::get('/dashboard','PagesController@index')->name('index');
        Route::resource('/items', 'ItemController');

        Route::post('/items/bulk/delete', 'ItemController@bulkDeleteItem')->name('items.bulk.delete');

        // item slug update route
        Route::put('/items/{item}/slug/update', 'ItemController@updateItemSlug')->name('item.slug.update');

        // item section & collection routes for admin
        Route::get('/items/{item}/sections/index', 'ItemController@indexItemSections')->name('items.sections.index');
        Route::post('/items/{item}/sections/store', 'ItemController@storeItemSection')->name('items.sections.store');
        Route::get('/items/{item}/sections/{item_section}/edit', 'ItemController@editItemSection')->name('items.sections.edit');
        Route::put('/items/{item}/sections/{item_section}/update', 'ItemController@updateItemSection')->name('items.sections.update');
        Route::delete('/items/{item}/sections/{item_section}/destroy', 'ItemController@destroyItemSection')->name('items.sections.destroy');

        Route::put('/items/{item}/sections/{item_section}/rank-up', 'ItemController@rankUpItemSection')->name('items.sections.rank.up');
        Route::put('/items/{item}/sections/{item_section}/rank-down', 'ItemController@rankDownItemSection')->name('items.sections.rank.down');

        Route::post('/items/{item}/sections/{item_section}/collections/store', 'ItemController@storeItemSectionCollections')->name('items.sections.collections.store');
        Route::put('/items/{item}/sections/{item_section}/collections/{item_section_collection}/rank-up', 'ItemController@rankUpItemSectionCollection')->name('items.sections.collections.rank.up');
        Route::put('/items/{item}/sections/{item_section}/collections/{item_section_collection}/rank-down', 'ItemController@rankDownItemSectionCollection')->name('items.sections.collections.rank.down');
        Route::delete('/items/{item}/sections/{item_section}/collections/{item_section_collection}/destroy', 'ItemController@destroyItemSectionCollection')->name('items.sections.collections.destroy');

        // item claims routes for user
        Route::resource('/item-claims', 'ItemClaimController');
        Route::post('/item-claims/download/{item_claim}', 'ItemClaimController@downloadItemClaimDoc')->name('item-claims.download.do');

        Route::put('/items/{item}/category/update', 'ItemController@updateItemCategory')->name('item.category.update');

        Route::get('/items/saved/index', 'ItemController@savedItems')->name('items.saved');
        Route::post('/items/{item_slug}/unsave', 'ItemController@unSaveItem')->name('items.unsave');

        // item reviews routes
        Route::get('/items/{item_slug}/reviews/create', 'ItemController@itemReviewsCreate')->name('items.reviews.create');
        Route::post('/items/{item_slug}/reviews/store', 'ItemController@itemReviewsStore')->name('items.reviews.store');
        Route::get('/items/{item_slug}/reviews/{review}/edit', 'ItemController@itemReviewsEdit')->name('items.reviews.edit');
        Route::put('/items/{item_slug}/reviews/update/{review}', 'ItemController@itemReviewsUpdate')->name('items.reviews.update');
        Route::delete('/items/{item_slug}/reviews/destroy/{review}', 'ItemController@itemReviewsDestroy')->name('items.reviews.destroy');

        // user manage reviews route
        Route::get('/items/reviews/index', 'ItemController@itemReviewsIndex')->name('items.reviews.index');

        // user item hours routes
        Route::put('/items/hours/update/{item_hour}', 'ItemController@updateItemHour')->name('items.hours.update');
        Route::delete('/items/hours/destroy/{item_hour}', 'ItemController@destroyItemHour')->name('items.hours.destroy');

        // user item hour exceptions routes
        Route::put('/items/hour-exceptions/update/{item_hour_exception}', 'ItemController@updateItemHourException')->name('items.hour-exceptions.update');
        Route::delete('/items/hour-exceptions/destroy/{item_hour_exception}', 'ItemController@destroyItemHourException')->name('items.hour-exceptions.destroy');

        // message routes
        Route::resource('/messages', 'MessageController');

        // subscription routes
        Route::resource('/subscriptions', 'SubscriptionController');

        // product routes
        Route::resource('/products', 'ProductController');
        Route::put('/products/{product}/attribute/update', 'ProductController@updateProductAttribute')->name('product.attribute.update');
        Route::put('/products/{product}/feature/{product_feature}/rank-up', 'ProductController@rankUpProductFeature')->name('product.feature.up');
        Route::put('/products/{product}/feature/{product_feature}/rank-down', 'ProductController@rankDownProductFeature')->name('product.feature.down');
        Route::delete('/products/{product}/feature/{product_feature}/destroy', 'ProductController@destroyProductFeature')->name('product.feature.destroy');

        // product attribute routes
        Route::resource('/attributes', 'AttributeController');

        Route::get('/comments', 'CommentController@index')->name('comments.index');

        /**
         * Start Payment Gateway Routes
         */

        // Bank Transfer manual payment gateway
        Route::post('/banktransfer/checkout/plan/{plan_id}/subscription/{subscription_id}', 'BankTransferController@doCheckout')->name('banktransfer.checkout.do');
        Route::post('/banktransfer/recurring/cancel', 'BankTransferController@cancelRecurring')->name('banktransfer.recurring.cancel');

        // PayPal gateway
        Route::get('/paypal/checkout/plan/{plan_id}/subscription/{subscription_id}', 'PaypalController@doCheckout')->name('paypal.checkout.do');
        Route::get('/paypal/checkout/success/plan/{plan_id}/subscription/{subscription_id}', 'PaypalController@showCheckoutSuccess')->name('paypal.checkout.success');
        Route::get('/paypal/checkout/cancel', 'PaypalController@showCheckoutCancel')->name('paypal.checkout.cancel');
        Route::post('/paypal/recurring/cancel', 'PaypalController@cancelRecurring')->name('paypal.recurring.cancel');

        // indian payment gateway - instamojo
        //Route::get('/instamojo/checkout/plan/{plan_id}/subscription/{subscription_id}', 'InstamojoController@doCheckout')->name('instamojo.checkout.do');
        //Route::get('/instamojo/checkout/success/plan/{plan_id}/subscription/{subscription_id}', 'InstamojoController@showCheckoutSuccess')->name('instamojo.checkout.success');

        // indian payment gateway - Razorpay
        Route::post('/razorpay/checkout/plan/{plan_id}/subscription/{subscription_id}', 'RazorpayController@doCheckout')->name('razorpay.checkout.do');
        Route::get('/razorpay/checkout/success/plan/{plan_id}/subscription/{subscription_id}/invoice/{invoice_num}', 'RazorpayController@showCheckoutSuccess')->name('razorpay.checkout.success');
        Route::post('/razorpay/checkout/cancel', 'RazorpayController@cancelCheckout')->name('razorpay.checkout.cancel');
        Route::post('/razorpay/recurring/cancel', 'RazorpayController@cancelRecurring')->name('razorpay.recurring.cancel');

        // Stripe payment gateway
        Route::post('/stripe/checkout/plan/{plan_id}/subscription/{subscription_id}', 'StripeController@doCheckout')->name('stripe.checkout.do');
        Route::get('/stripe/checkout/success/plan/{plan_id}/subscription/{subscription_id}', 'StripeController@showCheckoutSuccess')->name('stripe.checkout.success');
        Route::get('/stripe/checkout/cancel', 'StripeController@cancelCheckout')->name('stripe.checkout.cancel');
        Route::post('/stripe/recurring/cancel', 'StripeController@cancelRecurring')->name('stripe.recurring.cancel');

        // Payumoney payment gateway
        Route::post('/payumoney/checkout/plan/{plan_id}/subscription/{subscription_id}', 'PayumoneyController@doCheckout')->name('payumoney.checkout.do');
        Route::post('/payumoney/checkout/success', 'PayumoneyController@showCheckoutSuccess')->name('payumoney.checkout.success');
        Route::post('/payumoney/checkout/cancel', 'PayumoneyController@cancelCheckout')->name('payumoney.checkout.cancel');
        Route::post('/payumoney/recurring/cancel', 'PayumoneyController@cancelRecurring')->name('payumoney.recurring.cancel');
        /**
         * End Payment Gateway Routes
         */

        // user profile routes
        Route::get('/profile', 'UserController@editProfile')->name('profile.edit');
        Route::post('/profile', 'UserController@updateProfile')->name('profile.update');
        Route::get('/profile/password', 'UserController@editProfilePassword')->name('profile.password.edit');
        Route::post('/profile/password', 'UserController@updateProfilePassword')->name('profile.password.update');

        // item leads routes
        Route::resource('/item-leads', 'ItemLeadController');
    });

});
/**
 * End website routes
 */



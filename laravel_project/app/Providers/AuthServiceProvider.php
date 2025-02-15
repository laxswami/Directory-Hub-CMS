<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * start authorization checks
         */

        // site admin always can do anything
        Gate::before(function ($user) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        // jsonDeleteSettingLogoImage for users
        Gate::define('delete-setting-image-logo', function ($user) {
            return $user->isAdmin() ? true : false;
        });

        // jsonDeleteSettingFaviconImage for users
        Gate::define('delete-setting-image-favicon', function ($user) {
            return $user->isAdmin() ? true : false;
        });

        // jsonDeleteUserProfileImage for users
        Gate::define('delete-user-image-profile', function ($user, $gate_user) {
            return $user->id == $gate_user->id;
        });

        // jsonDeleteItemFeatureImage for users
        Gate::define('delete-item-image-feature', function ($user, $item) {

            $item_user = $item->user()->first();

            return $user->id == $item_user->id;
        });

        // jsonDeleteProductFeatureImage for users
        Gate::define('delete-product-image-feature', function ($user, $product) {

            $product_user = $product->user()->first();

            return $user->id == $product_user->id;
        });

        // jsonDeleteItemImageGallery for users
        Gate::define('delete-item-image-gallery', function ($user, $itemImageGallery) {

            $item = $itemImageGallery->item()->first();
            $item_user = $item->user()->first();

            return $user->id == $item_user->id;
        });

        // jsonDeleteProductImageGallery for users
        Gate::define('delete-product-image-gallery', function ($user, $productImageGallery) {

            $product = $productImageGallery->product()->first();
            $product_user = $product->user()->first();

            return $user->id == $product_user->id;
        });

        // Item model for users
        Gate::define('edit-item', function ($user, $item) {
            return $user->id == $item->user_id;
        });
        Gate::define('update-item', function ($user, $item) {
            return $user->id == $item->user_id;
        });
        Gate::define('delete-item', function ($user, $item) {
            return $user->id == $item->user_id;
        });

        // ItemClaim model for users
        Gate::define('edit-item-claim', function ($user, $item_claim) {
            return $user->id == $item_claim->user_id;
        });
        Gate::define('update-item-claim', function ($user, $item_claim) {
            return $user->id == $item_claim->user_id;
        });
        Gate::define('delete-item-claim', function ($user, $item_claim) {
            return $user->id == $item_claim->user_id;
        });
        Gate::define('download-item-claim', function ($user, $item_claim) {
            return $user->id == $item_claim->user_id;
        });

        // Attribute model for users
        Gate::define('edit-attribute', function ($user, $attribute) {
            return $user->id == $attribute->user_id;
        });
        Gate::define('update-attribute', function ($user, $attribute) {
            return $user->id == $attribute->user_id;
        });
        Gate::define('delete-attribute', function ($user, $attribute) {
            return $user->id == $attribute->user_id;
        });

        // Product model for users
        Gate::define('edit-product', function ($user, $product) {
            return $user->id == $product->user_id;
        });
        Gate::define('update-product', function ($user, $product) {
            return $user->id == $product->user_id;
        });
        Gate::define('delete-product', function ($user, $product) {
            return $user->id == $product->user_id;
        });

        // Category model for admin
        Gate::define('delete-category-image', function ($user) {
            return $user->isAdmin() ? true : false;
        });
    }
}

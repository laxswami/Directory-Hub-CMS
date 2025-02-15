<?php

return array (

    'seo' => [
        'index' => "Dashboard - Themes - :site_name",
        'color-edit' => "Dashboard - Edit Theme Color - :site_name",
        'header-edit' => "Dashboard - Edit Theme Header - :site_name",
        'install-theme' => "Dashboard - Install Theme - :site_name",
    ],

    'alert' => [
        'theme-activated' => "The theme has activated",
        'theme-color-updated' => "The theme colors have been updated",
        'theme-header-updated' => "The theme headers have been updated",
        'theme-color-restored' => "The theme colors have been restored to default values",
        'theme-header-restored' => "The theme headers have been restored to default values",
        'theme-delete-default-theme' => "Cannot delete the system default theme",
        'theme-deleted' => "Theme has been deleted",
        'theme-delete-confirm' => "Are you sure you want to delete this theme?",
        'theme-color-restore-confirm' => "Are you sure you want to restore to theme default colors?",
        'theme-header-restore-confirm' => "Are you sure you want to restore theme headers to default values?",
        'theme-install-failed-open-package' => "Failed to open theme install zip package",
        'theme-installed' => "New theme installed successfully",
        'theme-debug' => "Cannot delete theme in THEME_DEBUG mode.",
    ],

    'sidebar' => [
        'themes' => "Themes",
        'manage-themes' => "Manage Themes",
        'install-theme' => "Install Theme",
    ],

    'theme-description-directory-hub' => 'The Directory Hub Listing & Business Directory CMS default theme. Please use the Edit Colors and Edit Headers buttons to customize the theme styles of yours.',

    'theme-index' => "Manage Themes",
    'theme-index-desc' => "This page allows you manage website themes. You can install, customize, active, inactive, or delete themes in this page.",
    'theme-install' => "Install/Update Theme",

    'theme-detail' => "Theme Detail",
    'theme-active' => "Active",
    'theme-system-default' => "Default",

    'theme-modal-close' => "Close",

    'theme-edit-colors' => "Edit Colors",
    'theme-edit-headers' => "Edit Headers",

    'theme-current' => "Current Theme",
    'theme-by-author' => "By",

    'theme-edit-color' => "Edit Theme Colors",
    'theme-edit-color-desc' => "This page allows you edit the theme specific colors. You can also restore to default colors.",

    'theme-edit-header' => "Edit Theme Headers",
    'theme-edit-header-desc' => "This page allows you edit the theme specific headers. You can also restore to default values.",

    'theme-create' => "Install/Update Theme",
    'theme-create-desc' => "This page allows you to install a new theme or update an existing theme to the website.",
    'theme-install-label' => "Select theme install/update package",
    'theme-install-label-help' => "The theme install/update package should have filename called theme_package.zip",
    'theme-install-button' => "Submit",

    'filter-results' => "Results",
    'filter-filter-by' => "Filter by",
    'filter-button-filter-results' => "Filter Results",
    'filter-link-reset-all' => "Reset",
    'filter-sort-by-nearby-first' => "Nearby First",

    'online-listing' => [
        'regular-listing' => "Regular Listing",
        'online-listing' => "Online Listing",
        'regular-listing-help' => "For business that has a physical address",
        'online-listing-help' => "For business that entirely online with no physical address",
        'listing-type' => "Listing Type",
    ],

    'listing' => [
        'qr-code' => "QR Code",
        'gallery-upload-help' => "maximum file size: 5mb per image, maximum :gallery_photos_count images, if more than :gallery_photos_count images selected, first :gallery_photos_count images will be saved.",
    ],

    'plan' => [
        'alert' => [
            'plan-period' => "Plan period must in monthly, quarterly, or yearly.",
            'cannot-delete-free-plan' => "Cannot delete system default free plan",
            'free-plan-quota-reached' => "You have reached max free business listing quota.",
        ],

        'max-free-listing' => "Maximum Free Listing",
        'max-free-listing-help' => "Leave it empty for an unlimited free listing, enter 0 to prohibit free listing.",
        'max-featured-listing-help' => "Leave it empty for an unlimited featured listing, enter 0 to prohibit featured listing.",

        'free-plan' => "Free Plan",
        'paid-plan' => "Paid Plan",
        'unlimited' => "Unlimited",
        'free-listing' => "free listing",
        'featured-listing' => "featured listing",
        'subscription-edn-date-help' => "For free plan, the subscription end date will automatically set to null",

        'free-listing-cap' => "Free listing",

        'edit-plan-warning' => "Changes to this plan will apply to all users' subscriptions that in this plan. Please edit with caution.",
    ],

    'pricing' => [
        'seo' => [
            'pricing' => "Pricing - :site_name",
        ],

        'footer' => [
            'pricing' => "Pricing",
        ],

        'head-title' => "Pricing",
        'head-description' => "Choose a plan that's right for your business",
        'manage-pricing' => "Manage Pricing",
        'get-started' => "Get Started",
        'upgrade' => "Upgrade",
        'active' => "Active",

        'info-admin' => "You are viewing pricing tables as Administrator, you can manage website pricing plans by click the Manage Pricing button in each table below.",
        'info-user-paid' => "Woohoo! You had a paid subscription. Enjoy extra features brought by :site_name!",
        'info-user-free' => "You are currently in a free subscription, consider upgrading to a paid subscription to unlock extra features brought by :site_name!",

    ],

    'setting' => [
        'default-country' => "Default Country",
        'default-country-help' => "The default country to load listing data from on frontend website",

        'default-latitude' => "Default Latitude",
        'default-latitude-help' => "Default latitude for map selector",

        'default-longitude' => "Default Longitude",
        'default-longitude-help' => "Default longitude for map selector",

        'default-language' => "Default Language",
        'default-language-help' => "Default website language",

        'display-currency-symbol' => "Display Currency Symbol",
        'display-currency-symbol-help' => "The currency symbol to display for prices wherever applicable on website",

        'seo' => [
            'edit-item' => "Dashboard - Listing Setting - :site_name",
        ],

        'alert' => [
            'setting-item-updated' => "Listing setting updated successfully",
        ],

        'item-setting' => "Listing Setting",
        'item-setting-desc' => "This page allows you edit the configuration of listing.",

        'item-setting-auto-approval' => "Auto Approve",
        'item-setting-auto-approval-help' => "Auto approve listing submission for users",

        'item-setting-max-gallery-photos' => "Maximum Gallery Photos",
        'item-setting-max-gallery-photos-help' => "The total gallery photos allowed to upload (min:1 max:20)",

        'warning-smtp' => "Website SMTP information has not configured and enabled. Set up now to avoid system email delivery issues.",
        'warning-smtp-action' => "SMTP Setting",
    ],

    'importer' => [
        'basic' => "Basic",
        'custom-field' => "Custom Field",
        'import-custom-field-help' => "The importable custom fields depending on the category you select while importing. So you may not able to import all custom fields and values presented in this form.",
        'random-user' => "Pick up a random user",

        'import-error' => [
            'item-type-not-exist' => "A listing must in regular listing type or online listing type",
        ],
    ],

    'email' => [
        'alert' => [
            'sending-problem' => "We are having a problem sending out emails. If the problem persists, please contact the website administrator.",
        ],
    ],
);

<?php
/**
 * stripe payment gateway translation
 */
return array (
    'seo' => [
        'edit-stripe' => "Dashboard - Edit Stripe - :site_name",
        'edit-paypal' => "Dashboard - Edit PayPal - :site_name",
        'edit-razorpay' => "Dashboard - Edit Razorpay - :site_name",
    ],
    'alert' => [
        'payment-canceled' => "Payment canceled successfully.",
        'payment-success' => "Subscription paid successfully, please wait a few moment, and refresh this page to update your invoice and account subscription.",
        'value-required' => "required",
        'update-stripe-success' => "Stripe setting updated successfully.",
        'stripe-disable' => "Stripe payment gateway disabled.",
    ],
    'pay-redirect-message' => "Please wait...Opening Stripe payment page.",
    'pay-stripe' => "Pay with Stripe",

    'stripe' => "Stripe",

    'edit-stripe-setting' => "Edit Stripe Payment Gateway",
    'edit-stripe-setting-desc' => "This page allows you enable or disable Stripe payment gateway, and edit Stripe settings.",

    'enable-stripe' => "Enable Stripe payment gateway",
    'stripe-publishable-key' => "Stripe publishable key",
    'stripe-secret-key' => "Stripe secret key",
    'stripe-webhook-signing-secret' => "Stripe webhook signing secret",
    'stripe-currency-help' => "For support currency details, please check Stripe website.",

    'stripe-enabled' => "Stripe Enabled",
    'stripe-disabled' => "Stripe Disabled",

    'stripe-webhook' => "Stripe webhook",

    'stripe-webhook-events' => "Webhook events",
    'stripe-webhook-event-code' => "checkout.session.completed, invoice.paid, invoice.payment_failed",
);

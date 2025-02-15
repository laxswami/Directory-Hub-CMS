<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [

        /**
         * Start payment gateway webhooks
         */
        'paypal/notify',
        'razorpay/notify',
        'stripe/notify',
        'user/payumoney/checkout/success',
        'user/payumoney/checkout/cancel',
        'domain-verify'
        /**
         * End payment gateway webhooks
         */
    ];
}

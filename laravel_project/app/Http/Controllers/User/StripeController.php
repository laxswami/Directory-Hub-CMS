<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\Plan;
use App\Setting;
use App\StripeWebhookLog;
use App\Subscription;
use DateTime;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

class StripeController extends Controller
{
    protected $stripe_published_key;
    protected $stripe_secret_key;
    protected $stripe_webhook_signing_secret;
    protected $stripe_currency;

    public function initialStripe()
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_stripe_enable == Setting::SITE_PAYMENT_STRIPE_DISABLE)
        {
            \Session::flash('flash_message', __('stripe.alert.stripe-disable'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }

        $this->stripe_published_key = $settings->setting_site_stripe_publishable_key;
        $this->stripe_secret_key = $settings->setting_site_stripe_secret_key;
        $this->stripe_webhook_signing_secret = $settings->setting_site_stripe_webhook_signing_secret;
        $this->stripe_currency = $settings->setting_site_stripe_currency;
    }

    public function doCheckout(int $plan_id, int $subscription_id)
    {
        try
        {
            $this->initialStripe();

            $login_user = Auth::user();
            $current_subscription = new Subscription();
            if(!$current_subscription->planSubscriptionValidation($plan_id, $subscription_id, $login_user->id))
            {
                \Session::flash('flash_message', __('alert.paypal-plan-subscription-error'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('user.subscriptions.index');
            }

            $current_subscription = Subscription::find($subscription_id);

            if($current_subscription->plan()->first()->plan_type != Plan::PLAN_TYPE_FREE)
            {
                \Session::flash('flash_message', __('alert.paypal-free-plan-upgrade'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('user.subscriptions.index');
            }

            $stripe_published_key = $this->stripe_published_key;
            $stripe_secret_key = $this->stripe_secret_key;

            $future_plan = Plan::find($plan_id);
            $stripe_product_name = $future_plan->plan_name;
            $stripe_price_unit_amount = $future_plan->plan_price * 100;
            $stripe_price_currency = $this->stripe_currency;
            $stripe_interval_count = 1;
            $stripe_product_description = "";
            if($future_plan->plan_period == Plan::PLAN_MONTHLY)
            {
                $stripe_product_description = 'Monthly Subscription';
                $stripe_interval_count = 1;
            }
            if($future_plan->plan_period == Plan::PLAN_QUARTERLY)
            {
                $stripe_product_description = 'Quarterly Subscription';
                $stripe_interval_count = 3;
            }
            if($future_plan->plan_period == Plan::PLAN_YEARLY)
            {
                $stripe_product_description = 'Yearly Subscription';
                $stripe_interval_count = 12;
            }

            $stripe = new \Stripe\StripeClient($stripe_secret_key);

            // #1 - create a product record in Stripe
            $stripe_product = $stripe->products->create([
                'name' => $stripe_product_name,
                'description' => $stripe_product_description,
            ]);

            // #2 - create a price record for the product in Stripe
            $stripe_price = $stripe->prices->create([
                'unit_amount' => $stripe_price_unit_amount,
                'currency' => $stripe_price_currency,
                'recurring' => ['interval' => 'month', 'interval_count' => $stripe_interval_count],
                'product' => $stripe_product['id'],
            ]);

            // #3 - create a customer record in Stripe
            $stripe_customer = $stripe->customers->create([
                'name' => $login_user->name,
                'email' => $login_user->email,
            ]);

            // #4 - create a session record in Stripe
            $stripe_session = $stripe->checkout->sessions->create([
                'customer' => $stripe_customer['id'],
                'success_url' => route('user.stripe.checkout.success', ['plan_id' => $plan_id, 'subscription_id' => $subscription_id]),
                'cancel_url' => route('user.stripe.checkout.cancel'),
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price' => $stripe_price['id'],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'subscription',
            ]);
            $stripe_session_id = $stripe_session['id'];

            // #5 - insert the stripe customer_id and future plan_id to the subscription
            $current_subscription->subscription_stripe_customer_id = $stripe_customer['id'];
            $current_subscription->subscription_stripe_future_plan_id = $future_plan->id;
            $current_subscription->subscription_stripe_subscription_id = $stripe_session['subscription'];
            $current_subscription->subscription_pay_method = Subscription::PAY_METHOD_STRIPE;
            $current_subscription->save();

            return view('backend.user.subscription.payment.stripe.do-checkout',
                compact('stripe_published_key', 'stripe_session_id'));

        }
        catch (Exception $e) {

            Log::error($e);

            \Session::flash('flash_message', $e->getMessage());
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }
    }

    public function showCheckoutSuccess(int $plan_id, int $subscription_id)
    {
        $this->initialStripe();

        // We will verify the payment in notify function, here just simple redirect to subscription page with a
        // success message.
        \Session::flash('flash_message', __('stripe.alert.payment-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.subscriptions.index');
    }

    public function cancelCheckout()
    {
        $this->initialStripe();

        \Session::flash('flash_message', __('stripe.alert.payment-canceled'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.subscriptions.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ApiErrorException
     */
    public function cancelRecurring(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|max:255',
        ]);

        $subscription_id = $request->subscription_id;

        $subscription = Subscription::find($subscription_id);

        if($subscription)
        {
            $this->initialStripe();

            $login_user = Auth::user();

            if($login_user->id == $subscription->user_id)
            {
                if(!empty($subscription->subscription_stripe_subscription_id)
                    && $subscription->subscription_pay_method == Subscription::PAY_METHOD_STRIPE)
                {
                    $stripe_secret_key = $this->stripe_secret_key;
                    $stripe = new \Stripe\StripeClient($stripe_secret_key);

                    $stripe->subscriptions->cancel(
                        $subscription->subscription_stripe_subscription_id,
                        []
                    );
                }

                $subscription->plan_id = Plan::where('plan_type', Plan::PLAN_TYPE_FREE)
                    ->first()->id;

//                $subscription->subscription_pay_method = null;
//                $subscription->subscription_stripe_customer_id = null;
//                $subscription->subscription_stripe_subscription_id = null;
//                $subscription->subscription_stripe_future_plan_id = null;
                $subscription->save();

                \Session::flash('flash_message', __('alert.paypal-subscription-canceled'));
                \Session::flash('flash_type', 'success');

                return redirect()->route('user.subscriptions.index');
            }
            else
            {
                \Session::flash('flash_message', __('alert.paypal-subscription-user-not-match'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('user.subscriptions.index');
            }
        }
        else
        {
            \Session::flash('flash_message', __('alert.paypal-subscription-not-found'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }
    }

    /**
     * Stripe webhooks
     * @param Request $request
     * @throws Exception
     */
    public function notify(Request $request)
    {
        $this->initialStripe();

        //$stripe_secret_key = "sk_test_51HUNfIGwHz6tXHDfGoIJCd6PK3WYA7VtZ77e3OqE8K9GQemk8LMa1LrZQe2glJ1J8PfRJAeFJ45skzXB8ZRfey7k00xJiT3ggz";
        //$stripe = new \Stripe\StripeClient($stripe_secret_key);

        // You can find your endpoint's secret in your webhook settings
        $endpoint_secret = $this->stripe_webhook_signing_secret;

        $payload = @file_get_contents("php://input");
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try
        {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        }
        catch(\UnexpectedValueException $e)
        {
            // Invalid payload
            http_response_code(400);
            exit();
        }
        catch(\Stripe\Exception\SignatureVerificationException $e)
        {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        if($event)
        {
            // save raw webhook to database
            $stripe_webhook_log = new StripeWebhookLog();
            $stripe_webhook_log->stripe_webhook_log_value = $payload;
            $stripe_webhook_log->save();

            if($event->type == 'checkout.session.completed')
            {
                // Handle the checkout.session.completed event.
                // This event is sent when a customer clicks the Pay button in Checkout, informing you of a new purchase.

                $stripe_subscription_id = $event->data->object->subscription;
                $stripe_customer_id = $event->data->object->customer;

                // find the subscription record in subscriptions table
                $subscription = Subscription::where('subscription_stripe_customer_id', $stripe_customer_id)->get();

                if($subscription->count() == 0)
                {
                    http_response_code(404);
                    exit();
                }

                $subscription = $subscription->first();
                $subscription->subscription_stripe_subscription_id = $stripe_subscription_id;
                $subscription->save();
            }

            if($event->type == 'invoice.paid')
            {
                // The status of the invoice will show up as paid. Store the status in your
                // database to reference when a user accesses your service to avoid hitting rate
                // limits.

                // This event is sent each billing interval when a payment succeeds.

                // #1 - get the stripe customer_id
                $stripe_customer_id = $event->data->object->customer;
                $stripe_invoice_id = $event->data->object->id;
                $stripe_subscription_id = $event->data->object->subscription;

                $current_subscription = Subscription::where('subscription_stripe_customer_id', $stripe_customer_id)
                    ->where('subscription_stripe_subscription_id', $stripe_subscription_id)
                    ->where('subscription_pay_method', Subscription::PAY_METHOD_STRIPE)
                    ->get();

                if($current_subscription->count() == 0)
                {
                    http_response_code(404);
                    exit();
                }
                $current_subscription = $current_subscription->first();

                // #2 - create a new invoice in invoices table
                $future_plan = Plan::findOrFail($current_subscription->subscription_stripe_future_plan_id);

                $invoice_num = strtoupper('invoice_' . uniqid());
                $invoice = new Invoice();
                $invoice->subscription_id = $current_subscription->id;
                $invoice->invoice_num = $invoice_num;
                $invoice->invoice_item_title = $future_plan->plan_name;
                $invoice->invoice_item_description = $future_plan->plan_features;
                $invoice->invoice_amount = $future_plan->plan_price;
                $invoice->invoice_status = 'Paid';
                $invoice->invoice_pay_method = Subscription::PAY_METHOD_STRIPE;
                $invoice->invoice_stripe_invoice_id = $stripe_invoice_id;
                $invoice->save();

                // #3 - update subscription
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

//                $current_subscription->subscription_max_free_listing = is_null($future_plan->plan_max_free_listing) ? null : $future_plan->plan_max_free_listing;
//                $current_subscription->subscription_max_featured_listing = is_null($future_plan->plan_max_featured_listing) ? null : $future_plan->plan_max_featured_listing;

                $current_subscription->save();
            }

            if($event->type == 'invoice.payment_failed')
            {
                // This event is sent each billing interval if there is an issue with your customer’s payment method.

                $stripe_customer_id = $event->data->object->customer;
                $stripe_invoice_id = $event->data->object->id;
                $stripe_subscription_id = $event->data->object->subscription;

                $current_subscription = Subscription::where('subscription_stripe_customer_id', $stripe_customer_id)
                    ->where('subscription_stripe_subscription_id', $stripe_subscription_id)
                    ->where('subscription_pay_method', Subscription::PAY_METHOD_STRIPE)
                    ->get();

                if($current_subscription->count() == 0)
                {
                    http_response_code(404);
                    exit();
                }
                $current_subscription = $current_subscription->first();

                // #2 - create a new invoice in invoices table
                $future_plan = Plan::findOrFail($current_subscription->subscription_stripe_future_plan_id);

                $invoice_num = strtoupper('invoice_' . uniqid());
                $invoice = new Invoice();
                $invoice->subscription_id = $current_subscription->id;
                $invoice->invoice_num = $invoice_num;
                $invoice->invoice_item_title = $future_plan->plan_name;
                $invoice->invoice_item_description = $future_plan->plan_features;
                $invoice->invoice_amount = $future_plan->plan_price;
                $invoice->invoice_status = 'Failed';
                $invoice->invoice_pay_method = Subscription::PAY_METHOD_STRIPE;
                $invoice->invoice_stripe_invoice_id = $stripe_invoice_id;
                $invoice->save();
            }
        }

        http_response_code(200);
        exit();
    }
}

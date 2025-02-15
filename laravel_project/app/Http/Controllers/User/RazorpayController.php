<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\Plan;
use App\RazorpayWebhookLog;
use App\Setting;
use App\Subscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use DateTime;

class RazorpayController extends Controller
{
    protected $razorpay_api;
    protected $razorpay_api_key;
    protected $razorpay_api_secret;
    protected $razorpay_currency;

    public function initialRazorPay()
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_razorpay_enable == Setting::SITE_PAYMENT_RAZORPAY_DISABLE)
        {
            \Session::flash('flash_message', __('razorpay.alert.razorpay-disable'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }

        $this->razorpay_currency = $settings->setting_site_razorpay_currency;
        $this->razorpay_api_key = $settings->setting_site_razorpay_api_key;
        $this->razorpay_api_secret = $settings->setting_site_razorpay_api_secret;

        $this->razorpay_api = new Api($settings->setting_site_razorpay_api_key, $settings->setting_site_razorpay_api_secret);
    }

    public function doCheckout(int $plan_id, int $subscription_id)
    {
        try
        {
            $this->initialRazorPay();

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

            $future_plan = Plan::find($plan_id);

            $razorpay_api_key = $this->razorpay_api_key;
            $razorpay_plan_item_desc = "Subscription";
            $razorpay_interval = 1;
            $razorpay_currency = $this->razorpay_currency;
            $razorpay_plan_name = $future_plan->plan_name;
            $razorpay_plan_amount = $future_plan->plan_price * 100;
            if($future_plan->plan_period == Plan::PLAN_MONTHLY)
            {
                $razorpay_plan_item_desc = 'Monthly Subscription';
                $razorpay_interval = 1;
            }
            if($future_plan->plan_period == Plan::PLAN_QUARTERLY)
            {
                $razorpay_plan_item_desc = 'Quarterly Subscription';
                $razorpay_interval = 3;
            }
            if($future_plan->plan_period == Plan::PLAN_YEARLY)
            {
                $razorpay_plan_item_desc = 'Yearly Subscription';
                $razorpay_interval = 12;
            }

            $razorpay_plan = $this->razorpay_api->plan->create(array(
                    'period' => 'monthly',
                    'interval' => $razorpay_interval,
                    'item' => array(
                        'name' => $razorpay_plan_name,
                        'description' => $razorpay_plan_item_desc,
                        'amount' => $razorpay_plan_amount,
                        'currency' => $razorpay_currency,
                    )
                )
            );

            $razorpay_plan_id = $razorpay_plan['id'];

            $razorpay_subscription  = $this->razorpay_api->subscription->create(array(
                    'plan_id' => $razorpay_plan_id,
                    'customer_notify' => 1,
                    'total_count' => 1200/$razorpay_interval,
                )
            );

            $razorpay_subscription_id = $razorpay_subscription['id'];

            $invoice_num = strtoupper('invoice_' . strval($login_user->id) . uniqid());
            $invoice = new Invoice();
            $invoice->subscription_id = $subscription_id;
            $invoice->invoice_num = $invoice_num;
            $invoice->invoice_item_title = $razorpay_plan_name;
            $invoice->invoice_item_description = $future_plan->plan_features;
            $invoice->invoice_amount = $future_plan->plan_price;
            $invoice->invoice_status = 'Pending';
            $invoice->invoice_pay_method = Subscription::PAY_METHOD_RAZORPAY;
            $invoice->save();

            return view('backend.user.subscription.payment.razorpay.do-checkout',
                compact('razorpay_api_key', 'razorpay_subscription_id', 'razorpay_plan_name', 'razorpay_plan_item_desc',
                    'subscription_id', 'plan_id', 'invoice_num', 'razorpay_plan_id'));
        }
        catch (Exception $e) {

            Log::error($e);

            \Session::flash('flash_message', $e->getMessage());
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }
    }

    public function showCheckoutSuccess(Request $request)
    {
        try
        {
            $this->initialRazorPay();

            $plan_id = $request->plan_id;
            $subscription_id = $request->subscription_id;
            $invoice_num = $request->invoice_num;

            $current_subscription = new Subscription();
            if(!$current_subscription->planSubscriptionValidation($plan_id, $subscription_id, Auth::user()->id))
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

            $invoice = Invoice::where('invoice_num', $invoice_num)->get();

            if($invoice->count() == 0)
            {
                \Session::flash('flash_message', __('razorpay.alert.invoice-not-found'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('user.subscriptions.index');
            }
            else
            {
                $invoice = $invoice->first();
            }

            // start validate signature
            $razorpay_payment_id = $request->razorpay_payment_id;
            $razorpay_subscription_id = $request->razorpay_subscription_id ;
            $razorpay_signature = $request->razorpay_signature;
            $razorpay_plan_id = $request->razorpay_plan_id;

            $razorpay_api_secret = $this->razorpay_api_secret;

            $expected_signature = hash_hmac("sha256", $razorpay_payment_id . '|' . $razorpay_subscription_id, $razorpay_api_secret);

            if ($expected_signature != $razorpay_signature)
            {
                $invoice->invoice_status = "Canceled";
                $invoice->save();

                \Session::flash('flash_message', __('razorpay.alert.invalid-signature'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('user.subscriptions.index');
            }

            // update invoice
            $invoice->invoice_razorpay_payment_id = $razorpay_payment_id;
            $invoice->invoice_razorpay_signature = $razorpay_signature;
            $invoice->invoice_status = "Paid";
            $invoice->save();

            // update subscription
            $future_plan = Plan::find($plan_id);
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

//            $current_subscription->subscription_max_free_listing = is_null($future_plan->plan_max_free_listing) ? null : $future_plan->plan_max_free_listing;
//            $current_subscription->subscription_max_featured_listing = is_null($future_plan->plan_max_featured_listing) ? null : $future_plan->plan_max_featured_listing;

            $current_subscription->subscription_razorpay_plan_id = $razorpay_plan_id;
            $current_subscription->subscription_razorpay_subscription_id = $razorpay_subscription_id;
            $current_subscription->subscription_pay_method = Subscription::PAY_METHOD_RAZORPAY;

            $current_subscription->save();

            \Session::flash('flash_message', __('alert.paypal-order-paid', ['invoice_num' => $invoice->invoice_num]));
            \Session::flash('flash_type', 'success');

            return redirect()->route('user.subscriptions.index');
        }
        catch (Exception $e) {

            Log::error($e);

            \Session::flash('flash_message', $e->getMessage());
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }
    }

    public function cancelCheckout(Request $request)
    {
        $request->validate([
            'invoice_num' => 'required|max:255',
        ]);

        try
        {
            $this->initialRazorPay();

            $invoice_num = $request->invoice_num;

            $invoice = Invoice::where('invoice_num', $invoice_num)->get();

            if($invoice->count() > 0)
            {
                $invoice = $invoice->first();
                $invoice->invoice_status = "Canceled";
                $invoice->save();

                \Session::flash('flash_message', __('razorpay.alert.payment-canceled'));
                \Session::flash('flash_type', 'success');

                return redirect()->route('user.subscriptions.index');
            }
            else
            {
                return redirect()->route('user.subscriptions.index');
            }
        }
        catch (Exception $e) {

            Log::error($e);

            \Session::flash('flash_message', $e->getMessage());
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }
    }

    public function cancelRecurring(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|max:255',
        ]);

        try
        {
            $this->initialRazorPay();

            $subscription_id = $request->subscription_id;

            $subscription = Subscription::find($subscription_id);

            if($subscription)
            {
                $login_user = Auth::user();

                if($login_user->id == $subscription->user_id)
                {
                    if(!empty($subscription->subscription_razorpay_subscription_id)
                        && $subscription->subscription_pay_method == Subscription::PAY_METHOD_RAZORPAY)
                    {
                        $razorpay_subscription = $this->razorpay_api->subscription
                            ->fetch($subscription->subscription_razorpay_subscription_id)
                            ->cancel(['cancel_at_cycle_end' => 0]);
                    }

                    $subscription->plan_id = Plan::where('plan_type', Plan::PLAN_TYPE_FREE)
                        ->first()->id;
                    $subscription->subscription_razorpay_subscription_id = null;
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
        catch (Exception $e) {

            Log::error($e);

            \Session::flash('flash_message', $e->getMessage());
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }
    }

    /**
     * Razorpay webhooks
     * @param Request $request
     * @throws Exception
     */
    public function notify(Request $request)
    {
        $this->initialRazorPay();

        $data = file_get_contents("php://input");

        $event = json_decode($data, true);

        // save the webhook log
        $razorpay_webhook_log = new RazorpayWebhookLog();
        $razorpay_webhook_log->razorpay_webhook_log_value = $data;
        $razorpay_webhook_log->save();

        // subscription charged
        if($event['event'] == "subscription.charged")
        {
            // get razorpay subscription id
            $razorpay_subscription_id = $event['payload']['subscription']['entity']['id'];

            // get razorpay payment id
            $razorpay_payment_id = $event['payload']['payment']['entity']['id'];
            $razorpay_payment_amount = floatval($event['payload']['payment']['entity']['amount'])/100;

            // check if is a recurring charge
            $subscription = Subscription::where('subscription_razorpay_subscription_id', $razorpay_subscription_id)
                ->get();

            $invoice_exist = Invoice::where('invoice_razorpay_payment_id', $razorpay_payment_id)->get();

            if($subscription->count() > 0 && $invoice_exist->count() == 0)
            {
                $subscription = $subscription->first();

                $plan = $subscription->plan()->first();

                $invoice_num = strtoupper('invoice_' . strval($subscription->user_id) . uniqid());
                $invoice = new Invoice();
                $invoice->subscription_id = $subscription->id;
                $invoice->invoice_num = $invoice_num;
                $invoice->invoice_item_title = $plan->plan_name;;
                $invoice->invoice_item_description = $plan->plan_features;
                $invoice->invoice_amount = $razorpay_payment_amount;
                $invoice->invoice_razorpay_payment_id = $razorpay_payment_id;
                $invoice->invoice_status = 'Paid';
                $invoice->invoice_pay_method = Subscription::PAY_METHOD_RAZORPAY;
                $invoice->save();

                $today = new DateTime('now');
                if(!empty($subscription->subscription_end_date))
                {
                    $today = new DateTime($subscription->subscription_end_date);
                }
                if($plan->plan_period == Plan::PLAN_MONTHLY)
                {
                    $today->modify("+1 month");
                    $subscription->subscription_end_date = $today->format("Y-m-d");
                }
                if($plan->plan_period == Plan::PLAN_QUARTERLY)
                {
                    $today->modify("+3 month");
                    $subscription->subscription_end_date = $today->format("Y-m-d");
                }
                if($plan->plan_period == Plan::PLAN_YEARLY)
                {
                    $today->modify("+12 month");
                    $subscription->subscription_end_date = $today->format("Y-m-d");
                }
                $subscription->save();
            }
        }

    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\PaypalIpnLog;
use App\Plan;
use App\Setting;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\ExpressCheckout;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DateTime;
use Exception;

class PaypalController extends Controller
{
    protected $provider;

    public function initialPayPal()
    {
        //$settings = Setting::findOrFail(1);

        $settings = app('site_global_settings');

        if($settings->setting_site_paypal_enable == Setting::SITE_PAYMENT_PAYPAL_DISABLE)
        {
            \Session::flash('flash_message', __('payment.paypal-disable'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }

        // overwrite the configuration from settings table
        $this->provider = new ExpressCheckout();
        $paypal_config = [
            'mode'    => $settings->setting_site_paypal_mode, // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
            'sandbox' => [
                'username'    => $settings->setting_site_paypal_sandbox_username,
                'password'    => $settings->setting_site_paypal_sandbox_password,
                'secret'      => $settings->setting_site_paypal_sandbox_secret,
                'certificate' => $settings->setting_site_paypal_sandbox_certificate,
                'app_id'      => $settings->setting_site_paypal_sandbox_app_id, // Used for testing Adaptive Payments API in sandbox mode
            ],
            'live' => [
                'username'    => $settings->setting_site_paypal_live_username,
                'password'    => $settings->setting_site_paypal_live_password,
                'secret'      => $settings->setting_site_paypal_live_secret,
                'certificate' => $settings->setting_site_paypal_live_certificate,
                'app_id'      => $settings->setting_site_paypal_live_app_id, // Used for Adaptive Payments API
            ],

            'payment_action' => $settings->setting_site_paypal_payment_action, // Can only be 'Sale', 'Authorization' or 'Order'
            'currency'       => $settings->setting_site_paypal_currency,
            'billing_type'   => $settings->setting_site_paypal_billing_type,
            'notify_url'     => $settings->setting_site_paypal_notify_url, // Change this accordingly for your application.
            'locale'         => $settings->setting_site_paypal_locale, // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            'validate_ssl'   => $settings->setting_site_paypal_validate_ssl == 0 ? false : true, // Validate SSL when creating api client.
        ];
        $this->provider->setApiCredentials($paypal_config);
    }

    public function doCheckout(int $plan_id, int $subscription_id)
    {
        try
        {
            $this->initialPayPal();

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

            $future_plan = Plan::find($plan_id);
            $invoice_id = strtoupper('invoice_' . strval(Auth::user()->id) . uniqid());
            $subscription_desc = '';
            if($future_plan->plan_period == Plan::PLAN_MONTHLY)
            {
                $subscription_desc = 'Monthly Subscription';
            }
            if($future_plan->plan_period == Plan::PLAN_QUARTERLY)
            {
                $subscription_desc = 'Quarterly Subscription';
            }
            if($future_plan->plan_period == Plan::PLAN_YEARLY)
            {
                $subscription_desc = 'Yearly Subscription';
            }

            // check if payment is recurring
            //$recurring = $request->input('recurring', false) ? true : false;
            $recurring = true;

            // get new invoice id
//        $invoice_id = Invoice::count() + 1;

            // Get the cart data
//        $cart = $this->getCart($recurring, $invoice_id, $current_plan, $current_subscription);

            // create new invoice
//        $invoice = new Invoice();
//
//        $invoice->invoice_num = $cart['invoice_id'];
//        $invoice->invoice_item_title = $cart['invoice_id'];
//        $invoice->invoice_item_description = $cart['invoice_description'];
//        $invoice->price = $cart['total'];
//        $invoice->save();

            // send a request to paypal
            // paypal should respond with an array of data
            // the array should contain a link to paypal's payment system
            $response = $this->provider->setExpressCheckout(
                [
                    // if payment is recurring cart needs only one item
                    // with name, price and quantity
                    'items' => [
                        [
                            'name' => $future_plan->plan_name,
                            'price' => $future_plan->plan_price,
                            'qty' => 1,
                        ],
                    ],

                    // return url is the url where PayPal returns after user confirmed the payment
                    //'return_url' => url('/paypal/express-checkout-success?recurring=1'),
                    'return_url' => route('user.paypal.checkout.success',
                        ['plan_id' => $future_plan->id, 'subscription_id' => $current_subscription->id]),
                    //'subscription_desc' => 'Monthly Subscription ' . config('paypal.invoice_prefix') . ' #' . $invoice_id,
                    'subscription_desc' => $subscription_desc,
                    // every invoice id must be unique, else you'll get an error from paypal
                    //'invoice_id' => config('paypal.invoice_prefix') . '_' . $invoice_id,
                    'invoice_id' => $invoice_id,
                    'invoice_description' => "Order #". $invoice_id ." - " . $future_plan->plan_name,
                    //'cancel_url' => url('/'),
                    'cancel_url' => route('user.paypal.checkout.cancel'),
                    // total is calculated by multiplying price with quantity of all cart items and then adding them up
                    // in this case total is 20 because price is 20 and quantity is 1
                    'total' => $future_plan->plan_price, // Total price of the cart
                ], $recurring);

            // if there is no link redirect back with error message
            if (!$response['paypal_link']) {

                //return redirect('/')->with(['code' => 'danger', 'message' => 'Something went wrong with PayPal']);
                // For the actual error message dump out $response and see what's in there

                \Session::flash('flash_message', __('alert.paypal-wrong'));
                \Session::flash('flash_type', 'danger');

//            var_dump($response);
//            die();

                return redirect()->route('user.subscriptions.index');
            }

            // redirect to paypal
            // after payment is done paypal
            // will redirect us back to $this->expressCheckoutSuccess
            return redirect($response['paypal_link']);
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
            $this->initialPayPal();

            $plan_id = $request->plan_id;
            $subscription_id = $request->subscription_id;

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

            $future_plan = Plan::find($plan_id);
            $subscription_desc = '';
            $billing_frequency = 1;
            if($future_plan->plan_period == Plan::PLAN_MONTHLY)
            {
                $subscription_desc = 'Monthly Subscription';
                $billing_frequency = 1;
            }
            if($future_plan->plan_period == Plan::PLAN_QUARTERLY)
            {
                $subscription_desc = 'Quarterly Subscription';
                $billing_frequency = 3;
            }
            if($future_plan->plan_period == Plan::PLAN_YEARLY)
            {
                $subscription_desc = 'Yearly Subscription';
                $billing_frequency = 12;
            }

            // check if payment is recurring
            //$recurring = $request->input('recurring', false) ? true : false;
            $recurring = true;

            $token = $request->get('token');

            $PayerID = $request->get('PayerID');

            // initaly we paypal redirects us back with a token
            // but doesn't provice us any additional data
            // so we use getExpressCheckoutDetails($token)
            // to get the payment details
            $response = $this->provider->getExpressCheckoutDetails($token);

            // if response ACK value is not SUCCESS or SUCCESSWITHWARNING
            // we return back with error
            if (!in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {

                \Session::flash('flash_message', __('alert.paypal-process-payemnt-error'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('user.subscriptions.index');

                //return redirect('/')->with(['code' => 'danger', 'message' => 'Error processing PayPal payment']);
            }

            // invoice id is stored in INVNUM
            // because we set our invoice to be xxxx_id
            // we need to explode the string and get the second element of array
            // witch will be the id of the invoice
            $invoice_num = $response['INVNUM'];

            // get cart data
            //$cart = $this->getCart($recurring, $invoice_id, $current_plan, $current_subscription);

            // check if our payment is recurring
            if ($recurring == true) {

                // The $token is the value returned from SetExpressCheckout API call
                $startdate = Carbon::now()->toAtomString();
//            $profile_desc = !empty($data['subscription_desc']) ?
//                $data['subscription_desc'] : $data['invoice_description'];
                $data = [
                    'PROFILESTARTDATE' => $startdate,
                    'DESC' => $subscription_desc,
                    'BILLINGPERIOD' => 'Month', // Can be 'Day', 'Week', 'SemiMonth', 'Month', 'Year'
                    'BILLINGFREQUENCY' => $billing_frequency, //
                    'AMT' => $future_plan->plan_price, // Billing amount for each billing cycle
                    'CURRENCYCODE' => 'USD', // Currency code
                    'INITAMT' => $future_plan->plan_price,
                    'FAILEDINITAMTACTION' => 'CancelOnFailure',
                    //'TRIALBILLINGPERIOD' => 'Day',  // (Optional) Can be 'Day', 'Week', 'SemiMonth', 'Month', 'Year'
                    //'TRIALBILLINGFREQUENCY' => 10, // (Optional) set 12 for monthly, 52 for yearly
                    //'TRIALTOTALBILLINGCYCLES' => 1, // (Optional) Change it accordingly
                    //'TRIALAMT' => 0, // (Optional) Change it accordingly
                ];
                $response = $this->provider->createRecurringPaymentsProfile($data, $response['TOKEN']);

                // if recurring then we need to create the subscription
                // you can create monthly or yearly subscriptions
                //$response = $this->provider->createMonthlySubscription($response['TOKEN'], $response['AMT'], $cart['subscription_desc']);

                $status = 'Invalid';
                // if after creating the subscription paypal responds with activeprofile or pendingprofile
                // we are good to go and we can set the status to Processed, else status stays Invalid
                if (!empty($response['PROFILESTATUS']) && in_array($response['PROFILESTATUS'], ['ActiveProfile', 'PendingProfile'])) {
                    $status = 'Processed';
                }

            }
//        else {
//
//            // if payment is not recurring just perform transaction on PayPal
//            // and get the payment status
//            //$payment_status = $this->provider->doExpressCheckoutPayment($cart, $token, $PayerID);
//            //$status = $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'];
//
//        }


            // find invoice by id
            $invoice = new Invoice();
            $invoice->subscription_id = $current_subscription->id;
            $invoice->invoice_num = $invoice_num;
            $invoice->invoice_item_title = $future_plan->plan_name;
            $invoice->invoice_item_description = $future_plan->plan_features;
            $invoice->invoice_amount = $future_plan->plan_price;

            // set invoice status
            $invoice->invoice_status = $status;

            // if payment is recurring lets set a recurring id for latter use
            if ($recurring == true) {
                $invoice->subscription_paypal_profile_id = $response['PROFILEID'];
            }

            // save the invoice
            $invoice->save();

            // App\Invoice has a paid attribute that returns true or false based on payment status
            // so if paid is false return with error, else return with success message
            if ($invoice->paid()) {

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
                $current_subscription->subscription_paypal_profile_id = $response['PROFILEID'];
                $current_subscription->plan_id = $future_plan->id;

//                $current_subscription->subscription_max_free_listing = is_null($future_plan->plan_max_free_listing) ? null : $future_plan->plan_max_free_listing;
//                $current_subscription->subscription_max_featured_listing = is_null($future_plan->plan_max_featured_listing) ? null : $future_plan->plan_max_featured_listing;

                $current_subscription->save();

                //return redirect('/')->with(['code' => 'success', 'message' => 'Order ' . $invoice->id . ' has been paid successfully!']);

                \Session::flash('flash_message', __('alert.paypal-order-paid', ['invoice_num' => $invoice->invoice_num]));
                \Session::flash('flash_type', 'success');

                return redirect()->route('user.subscriptions.index');
            }

            //return redirect('/')->with(['code' => 'danger', 'message' => 'Error processing PayPal payment for Order ' . $invoice->id . '!']);

            \Session::flash('flash_message', __('alert.paypal-process-payemnt-error-order', ['invoice_num' => $invoice->invoice_num]));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }
        catch (Exception $e) {

            Log::error($e);

            \Session::flash('flash_message', $e->getMessage());
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }
    }

    public function showCheckoutCancel(Request $request)
    {
        try
        {
            $this->initialPayPal();

            \Session::flash('flash_message', __('alert.paypal-payment-canceled'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
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
            $this->initialPayPal();

            $subscription_id = $request->subscription_id;

            $subscription = Subscription::find($subscription_id);

            if($subscription)
            {
                $login_user = Auth::user();

                if($login_user->id == $subscription->user_id)
                {
                    if(!empty($subscription->subscription_paypal_profile_id))
                    {
                        // Cancel recurring payment profile
                        $response = $this->provider
                            ->cancelRecurringPaymentsProfile($subscription->subscription_paypal_profile_id);
                    }

                    $subscription->plan_id = Plan::where('plan_type', Plan::PLAN_TYPE_FREE)
                        ->first()->id;
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

    public function notify(Request $request)
    {
        $this->initialPayPal();

        // add _notify-validate cmd to request,
        // we need that to validate with PayPal that it was realy
        // PayPal who sent the request
        $request->merge(['cmd' => '_notify-validate']);
        $post = $request->all();

        // send the data to PayPal for validation
        $response = (string) $this->provider->verifyIPN($post);

        //if PayPal responds with VERIFIED we are good to go
        if ($response == 'VERIFIED') {

            /**
            This is the part of the code where you can process recurring payments as you like
            in this case we will be checking for recurring_payment that was completed
            if we find that data we create new invoice
             */
            if ($post['txn_type'] == 'recurring_payment' && $post['payment_status'] == 'Completed') {

                $profile_id = $post['recurring_payment_id'];
                $subscription = Subscription::where('subscription_paypal_profile_id', $profile_id)
                    ->get();
                if($subscription->count() == 1)
                {
                    $subscription = $subscription->first();

                    $plan = $subscription->plan()->first();

                    $invoice = new Invoice();
                    $invoice->subscription_id = $subscription->id;
                    $invoice->invoice_num = strtoupper('invoice_' . strval($subscription->user_id) . uniqid());
                    $invoice->invoice_item_title = $plan->plan_name;
                    $invoice->invoice_item_description = $plan->plan_features;
                    $invoice->invoice_amount = $post['amount'];
                    $invoice->invoice_status = 'Completed';
                    $invoice->subscription_paypal_profile_id = $profile_id;
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

            // I leave this code here so you can log IPN data if you want
            // PayPal provides a lot of IPN data that you should save in real world scenarios
            /*
                $logFile = 'ipn_log_'.Carbon::now()->format('Ymd_His').'.txt';
                Storage::disk('local')->put($logFile, print_r($post, true));
            */

            // save ipn log
            $paypal_ipn_log = new PaypalIpnLog();
            $paypal_ipn_log->paypal_ipn_log_value = json_encode($post);
            $paypal_ipn_log->save();
        }
    }

//    private function getCart($recurring, $invoice_id, $plan, $subscription)
//    {
//
//        if ($recurring) {
//            return [
//                // if payment is recurring cart needs only one item
//                // with name, price and quantity
//                'items' => [
//                    [
//                        'name' => $plan->plan_name . ' #' . $invoice_id,
//                        'price' => $plan->plan_price,
//                        'qty' => 1,
//                    ],
//                ],
//
//                // return url is the url where PayPal returns after user confirmed the payment
//                //'return_url' => url('/paypal/express-checkout-success?recurring=1'),
//                'return_url' => route('paypal.checkout.success',
//                    ['recurring' => 1, 'plan_id' => $plan->id, 'subscription_id' => $subscription->id]),
//                //'subscription_desc' => 'Monthly Subscription ' . config('paypal.invoice_prefix') . ' #' . $invoice_id,
//                'subscription_desc' => $plan->plan_features,
//                // every invoice id must be unique, else you'll get an error from paypal
//                //'invoice_id' => config('paypal.invoice_prefix') . '_' . $invoice_id,
//                'invoice_id' => '#_' . $invoice_id,
//                'invoice_description' => "Order #". $invoice_id ." Invoice",
//                //'cancel_url' => url('/'),
//                'cancel_url' => route('paypal.checkout.cancel'),
//                // total is calculated by multiplying price with quantity of all cart items and then adding them up
//                // in this case total is 20 because price is 20 and quantity is 1
//                'total' => $plan->plan_price, // Total price of the cart
//            ];
//        }
//
//        return [
//            // if payment is not recurring cart can have many items
//            // with name, price and quantity
//            'items' => [
//                [
//                    'name' => 'Product 1',
//                    'price' => 10,
//                    'qty' => 1,
//                ],
//                [
//                    'name' => 'Product 2',
//                    'price' => 5,
//                    'qty' => 2,
//                ],
//            ],
//
//            // return url is the url where PayPal returns after user confirmed the payment
//            'return_url' => url('/paypal/express-checkout-success'),
//            // every invoice id must be unique, else you'll get an error from paypal
//            'invoice_id' => config('paypal.invoice_prefix') . '_' . $invoice_id,
//            'invoice_description' => "Order #" . $invoice_id . " Invoice",
//            'cancel_url' => url('/'),
//            // total is calculated by multiplying price with quantity of all cart items and then adding them up
//            // in this case total is 20 because Product 1 costs 10 (price 10 * quantity 1) and Product 2 costs 10 (price 5 * quantity 2)
//            'total' => 20,
//        ];
//    }

//    private function planSubscriptionValidation($plan_id, $subscription_id)
//    {
//        $validation = true;
//        $plan_id_exist = Plan::where('id', $plan_id)
//            ->where('plan_type', Plan::PLAN_TYPE_PAID)
//            ->where('plan_status', Plan::PLAN_ENABLED)
//            ->get()->count();
//        if($plan_id_exist == 0)
//        {
//            $validation =  false;
//        }
//        $subscription_id_exist = Subscription::where('id', $subscription_id)
//            ->where('user_id', Auth::user()->id)
//            ->get()->count();
//        if($subscription_id_exist == 0)
//        {
//            $validation =  false;
//        }
//
//        return $validation;
//    }
}

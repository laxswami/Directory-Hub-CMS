<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\Plan;
use App\Setting;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use DateTime;

class PayumoneyController extends Controller
{
    const PAYUMONEY_LIVE_URL = 'https://secure.payu.in/_payment';
    const PAYUMONEY_TEST_URL = 'https://sandboxsecure.payu.in/_payment';

    protected $merchant_key;
    protected $salt;
    protected $mode;
    protected $action_url;

    public function initialPayUMoney()
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_payumoney_enable == Setting::SITE_PAYMENT_PAYUMONEY_DISABLE)
        {
            \Session::flash('flash_message', __('payumoney.alert.payumoney-disable'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }
        else
        {
            $this->merchant_key = $settings->setting_site_payumoney_merchant_key;
            $this->salt = $settings->setting_site_payumoney_salt;
            $this->mode = $settings->setting_site_payumoney_mode;

            if($this->mode == Setting::SITE_PAYMENT_PAYUMONEY_MODE_LIVE)
            {
                $this->action_url = self::PAYUMONEY_LIVE_URL;
            }
            else
            {
                $this->action_url = self::PAYUMONEY_TEST_URL;
            }
        }


    }

    public function doCheckout(int $plan_id, int $subscription_id)
    {
        try
        {
            $this->initialPayUMoney();

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

            /**
             * Start initial payumoney parameters
             */
            $merchant_key = $this->merchant_key;
            $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
            $salt = $this->salt;
            $action_url = $this->action_url;
            $amount = $future_plan->plan_price;
            $productinfo = '';
            if($future_plan->plan_period == Plan::PLAN_MONTHLY)
            {
                $productinfo = 'Monthly Subscription';
            }
            if($future_plan->plan_period == Plan::PLAN_QUARTERLY)
            {
                $productinfo = 'Quarterly Subscription';
            }
            if($future_plan->plan_period == Plan::PLAN_YEARLY)
            {
                $productinfo = 'Yearly Subscription';
            }
            $firstname = $login_user->name;
            $email = $login_user->email;
            $phone = '0000000000';
            $surl = route('user.payumoney.checkout.success', ['plan_id' => $future_plan->id, 'subscription_id' => $current_subscription->id]);
            $furl = route('user.payumoney.checkout.cancel');

            $hash_string = $merchant_key.'|'.$txnid.'|'.strval($amount).'|'.$productinfo.'|'.$firstname.'|'.$email.'|||||||||||'.$salt;
            $hash = strtolower(hash('sha512', $hash_string));
            /**
             * End initial payumoney parameters
             */

            $invoice_num = strtoupper('invoice_' . strval($login_user->id) . uniqid());
            $invoice = new Invoice();
            $invoice->subscription_id = $subscription_id;
            $invoice->invoice_num = $invoice_num;
            $invoice->invoice_item_title = $productinfo;
            $invoice->invoice_item_description = $future_plan->plan_features;
            $invoice->invoice_amount = $amount;
            $invoice->invoice_status = 'Pending';
            $invoice->invoice_pay_method = Subscription::PAY_METHOD_PAYUMONEY;
            $invoice->invoice_payumoney_transaction_id = $txnid;
            $invoice->invoice_payumoney_future_plan_id = $future_plan->id;
            $invoice->save();

            return view('backend.user.subscription.payment.payumoney.do-checkout',
                compact('merchant_key', 'txnid', 'salt', 'action_url', 'amount', 'productinfo', 'firstname',
                    'email', 'phone', 'surl', 'furl', 'hash', 'plan_id', 'subscription_id'));

        }
        catch (\Exception $e) {

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
            $this->initialPayUMoney();

            $status = $request->status;
            $firstname = $request->firstname;
            $amount = $request->amount;
            $txnid = $request->txnid;
            $posted_hash = $request->hash;
            $key = $request->key;
            $productinfo = $request->productinfo;
            $email = $request->email;
            $salt = $this->salt;

            /**
             * Start initial hash
             */
            if (isset($request->additionalCharges))
            {
                $additionalCharges=$request->additionalCharges;
                $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
            }
            else
            {
                $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
            }
            $hash = hash("sha512", $retHashSeq);
            /**
             * End initial hash
             */

            if($hash != $posted_hash)
            {
                \Session::flash('flash_message', __('payumoney.alert.invalid-transaction'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('user.subscriptions.index');
            }
            else
            {
                $invoice = Invoice::where('invoice_payumoney_transaction_id', $txnid)->get();
                if($invoice->count() > 0)
                {
                    $invoice = $invoice->first();
                }
                $invoice->invoice_status = "Paid";
                $invoice->save();

                $current_subscription = Subscription::find($invoice->subscription_id);
                $future_plan = Plan::find($invoice->invoice_payumoney_future_plan_id);

                // update subscription
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

                $current_subscription->subscription_pay_method = Subscription::PAY_METHOD_PAYUMONEY;

                $current_subscription->save();

                \Session::flash('flash_message', __('payumoney.alert.payment-paid'));
                \Session::flash('flash_type', 'success');

                return redirect()->route('user.subscriptions.index');
            }

        }
        catch (\Exception $e) {

            Log::error($e);

            \Session::flash('flash_message', $e->getMessage());
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }
    }

    public function cancelCheckout(Request $request)
    {
        $txnid = $request->txnid;

        $invoice = Invoice::where('invoice_payumoney_transaction_id', $txnid)->get();
        if($invoice->count() > 0)
        {
            $invoice = $invoice->first();
        }
        $invoice->invoice_status = "Cancelled";
        $invoice->save();

        \Session::flash('flash_message', __('payumoney.alert.payment-canceled'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.subscriptions.index');
    }

    public function cancelRecurring(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|max:255',
        ]);

        try
        {
            $subscription_id = $request->subscription_id;
            $subscription = Subscription::find($subscription_id);

            if($subscription)
            {
                $login_user = Auth::user();

                if($login_user->id == $subscription->user_id)
                {
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
        catch (\Exception $e) {

            Log::error($e);

            \Session::flash('flash_message', $e->getMessage());
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }
    }
}

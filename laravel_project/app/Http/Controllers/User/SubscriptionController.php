<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Plan;
use App\Setting;
use App\SettingBankTransfer;
use App\Subscription;
use Artesaos\SEOTools\Facades\SEOMeta;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class SubscriptionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        //SEOMeta::setTitle('Dashboard - Subscriptions - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.user.subscription.subscriptions', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        // show subscription information for current user
        $login_user = Auth::user();
        $subscription = $login_user->subscription()->first();

        //$invoices = $subscription->invoices()->orderBy('created_at', 'DESC')->get();
        $invoices = $subscription->invoices()->latest('created_at')->get();

        $paid_subscription_days_left = $login_user->subscriptionDaysLeft();

        return response()->view('backend.user.subscription.index',
            compact('subscription', 'invoices', 'paid_subscription_days_left'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        return redirect()->route('user.subscriptions.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        return redirect()->route('user.subscriptions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Subscription $subscription)
    {
        return redirect()->route('user.subscriptions.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.user.subscription.edit-subscription', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_plans = Plan::where('id', '!=', $subscription->plan_id)
            ->where('plan_type', Plan::PLAN_TYPE_PAID)
            ->where('plan_status', Plan::PLAN_ENABLED)
            ->get();

        // get payment gateway enable status
        $setting_site_paypal_enable = $settings->setting_site_paypal_enable;
        $setting_site_razorpay_enable = $settings->setting_site_razorpay_enable;
        $setting_site_stripe_enable = $settings->setting_site_stripe_enable;
        $setting_site_payumoney_enable = $settings->setting_site_payumoney_enable;

        $all_setting_bank_transfers = SettingBankTransfer::where('setting_bank_transfer_status', Setting::SITE_PAYMENT_BANK_TRANSFER_ENABLE)->get();
        $setting_site_bank_transfer_enable = $all_setting_bank_transfers->count() > 0 ? true : false;

        return response()->view('backend.user.subscription.edit',
            compact('subscription', 'all_plans', 'setting_site_paypal_enable', 'setting_site_razorpay_enable',
                    'setting_site_stripe_enable', 'all_setting_bank_transfers', 'setting_site_bank_transfer_enable',
                    'setting_site_payumoney_enable'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Subscription $subscription
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, Subscription $subscription)
    {
        $plan_id = $request->plan_id;

        // validate plan_id
        if(empty($plan_id))
        {
            \Session::flash('flash_message', __('alert.subscription-choose-plan'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.edit', $subscription->id);
        }

        // validate plan_id exist
        $plan_id_exist = Plan::where('id', $plan_id)
            ->where('plan_status', Plan::PLAN_ENABLED)
            ->whereIn('plan_type', [Plan::PLAN_TYPE_FREE, Plan::PLAN_TYPE_PAID])
            ->count();

        if($plan_id_exist == 0)
        {
            \Session::flash('flash_message', __('alert.plan-not-exist'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.edit', $subscription->id);
        }

        // TODO
        // start PayPal payment gateway process

        // update plan_id to the subscription record
        $subscription->plan_id = $plan_id;
        // update subscription_end_date
        $select_plan = Plan::find($plan_id);

        $today = new DateTime('now');
        if(!empty($subscription->subscription_end_date))
        {
            $today = new DateTime($subscription->subscription_end_date);
        }

        if($select_plan->plan_period == Plan::PLAN_MONTHLY)
        {
            $today->modify("+1 month");
            $subscription->subscription_end_date = $today->format("Y-m-d");
        }
        if($select_plan->plan_period == Plan::PLAN_QUARTERLY)
        {
            $today->modify("+3 month");
            $subscription->subscription_end_date = $today->format("Y-m-d");
        }
        if($select_plan->plan_period == Plan::PLAN_YEARLY)
        {
            $today->modify("+12 month");
            $subscription->subscription_end_date = $today->format("Y-m-d");
        }
        $subscription->save();

        \Session::flash('flash_message', __('alert.plan-switched'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.subscriptions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Subscription $subscription)
    {
        return redirect()->route('user.subscriptions.index');
    }
}

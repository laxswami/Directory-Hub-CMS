<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Plan;
use App\Subscription;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

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
        SEOMeta::setTitle(__('seo.backend.admin.subscription.subscriptions', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        // do not show admin subscription
        $admin_plan = Plan::where('plan_type', Plan::PLAN_TYPE_ADMIN)->first();
        $all_subscription = Subscription::where('plan_id', '!=', $admin_plan->id)
            ->orderBy('subscription_end_date', 'desc')->get();

        return response()->view('backend.admin.subscription.index',
            compact('all_subscription'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        return redirect()->route('admin.subscriptions.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        return redirect()->route('admin.subscriptions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Subscription $subscription)
    {
        // TODO
        // will develop this page to include invoices
        return redirect()->route('admin.subscriptions.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        $plan = $subscription->plan()->first();
        if($plan->plan_type == Plan::PLAN_TYPE_ADMIN)
        {
            // cannot edit subscription for admin plan
            return redirect()->route('admin.subscriptions.index');
        }
        else
        {
            $settings = app('site_global_settings');

            /**
             * Start SEO
             */
            SEOMeta::setTitle(__('seo.backend.admin.subscription.edit-subscription', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
            SEOMeta::setDescription('');
            SEOMeta::setCanonical(URL::current());
            SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
            /**
             * End SEO
             */

            $all_plans = Plan::whereIn('plan_type', [Plan::PLAN_TYPE_FREE, Plan::PLAN_TYPE_PAID])
                ->where('plan_status', Plan::PLAN_ENABLED)
                ->orderBy('plan_type')
                ->orderBy('plan_period')
                ->get();

            return response()->view('backend.admin.subscription.edit',
                compact('subscription', 'all_plans'));
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Subscription $subscription
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Subscription $subscription)
    {
        $plan = $subscription->plan()->first();
        if($plan->plan_type == Plan::PLAN_TYPE_ADMIN)
        {
            return redirect()->route('admin.subscriptions.index');
        }
        else
        {
            $request->validate([
                'plan_id' => 'required|numeric',
                'subscription_end_date' => 'nullable|date_format:Y-m-d',
            ]);

            $plan_id = $request->plan_id;
            $subscription_end_date = $request->subscription_end_date;

            $validate_error = array();
            $plan_id_exist = Plan::where('id', $plan_id)
                ->whereIn('plan_type', [Plan::PLAN_TYPE_FREE, Plan::PLAN_TYPE_PAID])
                ->count();

            if($plan_id_exist == 0)
            {
                $validate_error['plan_id'] = 'Plan not found.';
            }

            if(count($validate_error) > 0)
            {
                throw ValidationException::withMessages($validate_error);
            }
            else
            {
                $plan_find = Plan::find($plan_id);

                $plan_max_free_listing = $plan_find->plan_max_free_listing;
                $plan_max_featured_listing = $plan_find->plan_max_featured_listing;

                $subscription->plan_id = $plan_id;
                $subscription->subscription_end_date = $plan_find->plan_type == Plan::PLAN_TYPE_FREE ? null : $subscription_end_date;

//                $subscription->subscription_max_featured_listing = is_null($plan_max_featured_listing) ? null : $plan_max_featured_listing;
//                $subscription->subscription_max_free_listing = is_null($plan_max_free_listing) ? null : $plan_max_free_listing;

                $subscription->save();

                \Session::flash('flash_message', __('alert.subscription-updated'));
                \Session::flash('flash_type', 'success');

                return redirect()->route('admin.subscriptions.edit', ['subscription' => $subscription]);
            }
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}

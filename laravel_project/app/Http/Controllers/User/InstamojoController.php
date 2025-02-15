<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\Plan;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InstamojoController extends Controller
{
    public function doCheckout(int $plan_id, int $subscription_id)
    {
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

        // Fetch the future plan
        $future_plan = Plan::find($plan_id);
        //$invoice_id = strtoupper('invoice_' . strval(Auth::user()->id) . uniqid());
//        $subscription_desc = '';
//        if($future_plan->plan_period == Plan::PLAN_MONTHLY)
//        {
//            $subscription_desc = 'Monthly Subscription';
//        }
//        if($future_plan->plan_period == Plan::PLAN_QUARTERLY)
//        {
//            $subscription_desc = 'Quarterly Subscription';
//        }
//        if($future_plan->plan_period == Plan::PLAN_YEARLY)
//        {
//            $subscription_desc = 'Yearly Subscription';
//        }

        // Start making request to Instamojo
        $api = new \Instamojo\Instamojo(
            '',
            '',
            ''
        );

        try
        {
            $response = $api->paymentRequestCreate(array(
                "purpose" => $future_plan->plan_name,
                "amount" => $future_plan->plan_price,
                "buyer_name" => $login_user->name,
                "send_email" => true,
                "email" => $login_user->email,
                "phone" => "",
                "redirect_url" => route('user.instamojo.checkout.success',
                    ['plan_id' => $future_plan->id, 'subscription_id' => $current_subscription->id]),
            ));

            $payment_request_id = $response['payment_request_id'];

            $invoice = new Invoice();
            $invoice->subscription_id = $subscription_id;
            $invoice->invoice_num = strtoupper('invoice_' . strval($login_user->id) . uniqid());
            $invoice->invoice_item_title = $future_plan->plan_name;
            $invoice->invoice_item_description = $future_plan->plan_features;
            $invoice->invoice_amount = $future_plan->plan_price;
            $invoice->invoice_status = 'Pending';
            $invoice->instamojo_payment_request_id = $payment_request_id;
            $invoice->save();

            return redirect($response['longurl']);

        }
        catch (Exception $e)
        {
            //print('Error: ' . $e->getMessage());

            \Session::flash('flash_message', __('instamojo.alert.instamojo-wrong') . " " . $e->getMessage());
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }

    }

    public function showCheckoutSuccess(Request $request)
    {
        try
        {
            // get payment_request_id and payment_id and payment_status
            $payment_request_id = $request->payment_request_id;
            $payment_id = $request->payment_id;
            $payment_status = $request->payment_status;

//            $api = new \Instamojo\Instamojo(
//                '',
//                '',
//                ''
//            );
//
//            $response = $api->paymentRequestStatus(request('payment_request_id'));
//
//            if( !isset($response['payments'][0]['status']) )
//            {
//                dd('payment failed');
//            }
//            else if($response['payments'][0]['status'] != 'Credit')
//            {
//                dd('payment failed');
//            }

            if($payment_status == "Credit")
            {
                \Session::flash('flash_message', __('instamojo.alert.instamojo-success'));
                \Session::flash('flash_type', 'success');
            }
            elseif($payment_status == "Failed")
            {
                \Session::flash('flash_message', __('instamojo.alert.instamojo-failed'));
                \Session::flash('flash_type', 'danger');
            }

            return redirect()->route('user.subscriptions.index');
        }
        catch (\Exception $e)
        {
            \Session::flash('flash_message', __('instamojo.alert.instamojo-failed') . " " . $e->getMessage());
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.index');
        }
    }

    public function notify(Request $request)
    {
        /*
         * Basic PHP script to handle Instamojo RAP webhook.
         */
        $data = $_POST;
        $mac_provided = $data['mac'];  // Get the MAC from the POST data
        unset($data['mac']);  // Remove the MAC key from the data.
        $ver = explode('.', phpversion());
        $major = (int) $ver[0];
        $minor = (int) $ver[1];
        if($major >= 5 and $minor >= 4)
        {
            ksort($data, SORT_STRING | SORT_FLAG_CASE);
        }
        else
        {
            uksort($data, 'strcasecmp');
        }

        // You can get the 'salt' from Instamojo's developers page(make sure to log in first): https://www.instamojo.com/developers
        // Pass the 'salt' without <>
        $mac_calculated = hash_hmac("sha1", implode("|", $data), "<YOUR_SALT>");
        if($mac_provided == $mac_calculated)
        {
            $invoice_status = 'Pending';
            $payment_request_id = $data['payment_request_id'];
            $payment_id = $data['payment_id'];

            if($data['status'] == "Credit")
            {
                // Payment was successful, mark it as successful in your database.
                // You can access payment_request_id, purpose etc here.
                $invoice_status = 'Paid';
            }
            else
            {
                // Payment was unsuccessful, mark it as failed in your database.
                // You can access payment_request_id, purpose etc here.
                $invoice_status = 'Failed';
            }
            $invoice = Invoice::where('instamojo_payment_request_id')->first();

            if($invoice)
            {
                $invoice->invoice_status = $invoice_status;
                $invoice->instamojo_payment_request_id = $payment_request_id;
                $invoice->instamojo_payment_id = $payment_id;
                $invoice->save();
            }

            $subscription = Subscription::find($invoice->subscription_id);
            if($subscription)
            {
                $plan = $subscription->plan()->first();

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
        else
        {
            Log::error("Instamojo webhook - MAC mismatch");
        }
    }
}

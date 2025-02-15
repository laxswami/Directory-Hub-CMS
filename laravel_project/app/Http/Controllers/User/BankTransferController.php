<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\Plan;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankTransferController extends Controller
{

    public function doCheckout(Request $request, int $plan_id, int $subscription_id)
    {
        $request->validate([
            'invoice_bank_transfer_bank_name' => 'nullable|max:255',
            'invoice_bank_transfer_detail' => 'nullable|max:65534',
        ]);

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

        $invoice_num = strtoupper('invoice_' . strval($login_user->id) . uniqid());
        $invoice = new Invoice();
        $invoice->subscription_id = $subscription_id;
        $invoice->invoice_num = $invoice_num;
        $invoice->invoice_item_title = $future_plan->plan_name;
        $invoice->invoice_item_description = $future_plan->plan_features;
        $invoice->invoice_amount = $future_plan->plan_price;
        $invoice->invoice_status = Invoice::INVOICE_STATUS_PENDING;
        $invoice->invoice_pay_method = Subscription::PAY_METHOD_BANK_TRANSFER;
        $invoice->invoice_bank_transfer_bank_name = $request->invoice_bank_transfer_bank_name;
        $invoice->invoice_bank_transfer_detail = $request->invoice_bank_transfer_detail;
        $invoice->invoice_bank_transfer_future_plan_id = $plan_id;
        $invoice->save();

        \Session::flash('flash_message', __('bank_transfer.alert.invoice-created', ['invoice_num' => $invoice->invoice_num]));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.subscriptions.index');
    }


    public function cancelRecurring(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|max:255',
        ]);
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
}

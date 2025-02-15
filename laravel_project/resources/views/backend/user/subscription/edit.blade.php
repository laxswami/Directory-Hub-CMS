@extends('backend.user.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.subscription.switch-plan') }}</h1>
            <p class="mb-4">{{ __('backend.subscription.switch-plan-desc-user') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('user.subscriptions.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            @if($subscription->plan->plan_type == \App\Plan::PLAN_TYPE_PAID)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ __('backend.subscription.switch-plan-help') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif


                <div class="row justify-content-center">


                    @foreach($all_plans as $plans_key => $plan)
                        <div class="col-10 col-md-6 col-lg-4">
                            <div class="card mb-4 box-shadow text-center">
                                <div class="card-header">
                                    <h4 class="my-0 font-weight-normal">
                                        @if(!empty($login_user))
                                            @if($login_user->isUser())

                                                @if($login_user->hasPaidSubscription())
                                                    @if($login_user->subscription->plan->id == $plan->id)
                                                        <span class="text-success">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                    @endif
                                                @else
                                                    @if($plan->plan_type == \App\Plan::PLAN_TYPE_FREE)
                                                        <span class="text-success">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                    @endif
                                                @endif

                                            @endif
                                        @endif

                                        {{ $plan->plan_name }}
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <h1 class="card-title pricing-card-title">{{ $site_global_settings->setting_product_currency_symbol . $plan->plan_price }}
                                        <small class="text-muted">/
                                            @if($plan->plan_period == \App\Plan::PLAN_LIFETIME)
                                                {{ __('backend.plan.lifetime') }}
                                            @elseif($plan->plan_period == \App\Plan::PLAN_MONTHLY)
                                                {{ __('backend.plan.monthly') }}
                                            @elseif($plan->plan_period == \App\Plan::PLAN_QUARTERLY)
                                                {{ __('backend.plan.quarterly') }}
                                            @else
                                                {{ __('backend.plan.yearly') }}
                                            @endif
                                        </small>
                                    </h1>
                                    <ul class="list-unstyled mt-3 mb-4">
                                        @if(is_null($plan->plan_max_free_listing))
                                            <li>
                                                {{ __('theme_directory_hub.plan.unlimited') . ' ' . __('theme_directory_hub.plan.free-listing') }}
                                            </li>
                                        @else
                                            <li>
                                                {{ $plan->plan_max_free_listing . ' ' . __('theme_directory_hub.plan.free-listing') }}
                                            </li>
                                        @endif

                                        @if(is_null($plan->plan_max_featured_listing))
                                            <li>
                                                {{ __('theme_directory_hub.plan.unlimited') . ' ' . __('theme_directory_hub.plan.featured-listing') }}
                                            </li>
                                        @else
                                            <li>
                                                {{ $plan->plan_max_featured_listing . ' ' . __('theme_directory_hub.plan.featured-listing') }}
                                            </li>
                                        @endif

                                        @if(!empty($plan->plan_features))
                                            @php
                                                $plan_features_array = [];
                                                foreach(preg_split("/((\r?\n)|(\r\n?))/", $plan->plan_features) as $plan_features_line)
                                                {
                                                    $plan_features_array[] = $plan_features_line;
                                                }
                                            @endphp

                                            @foreach($plan_features_array as $plan_features_array_key => $a_plan_feature)
                                                <li>
                                                    {{ $a_plan_feature }}
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>

                                    @if($plan->plan_type == \App\Plan::PLAN_TYPE_PAID)

                                        @if($setting_site_bank_transfer_enable == \App\Setting::SITE_PAYMENT_BANK_TRANSFER_ENABLE)
                                            <div class="row pb-3">
                                                <div class="col-12">
                                                    <a class="btn btn-sm btn-success btn-block text-white{{ $subscription->plan->plan_type == \App\Plan::PLAN_TYPE_PAID ? ' disabled' : '' }}" href="#" data-toggle="modal" data-target="#banktransferModal{{ strval($plan->id) }}">
                                                        {{ __('bank_transfer.pay-bank-transfer') }}
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="banktransferModal{{ strval($plan->id) }}" tabindex="-1" role="dialog" aria-labelledby="banktransferModal{{ strval($plan->id) }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">{{ __('bank_transfer.bank-transfer') }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST" action="{{ route('user.banktransfer.checkout.do', ['plan_id'=>$plan->id, 'subscription_id'=>$subscription->id]) }}" class="">
                                                            @csrf

                                                            <input type="hidden" name="invoice_bank_transfer_bank_name" id="invoice_bank_transfer_bank_name_{{ $plan->id }}" value="{{ $all_setting_bank_transfers->first()->setting_bank_transfer_bank_name }}">
                                                            <div class="modal-body">

                                                                <div class="row form-group">
                                                                    <div class="col-md-12 text-left">
                                                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                                                                            @foreach($all_setting_bank_transfers as $key_1 => $bank_transfer_1)
                                                                                @if($key_1 == 0)
                                                                                    <a class="nav-link active bank_transfer_tab" id="v-pills-tab-{{ $bank_transfer_1->id }}-{{ $plan->id }}" data-toggle="pill" href="#v-pills-{{ $bank_transfer_1->id }}-{{ $plan->id }}" role="tab" aria-controls="v-pills-{{ $bank_transfer_1->id }}-{{ $plan->id }}" aria-selected="true" data-input-id="invoice_bank_transfer_bank_name_{{ $plan->id }}">{{ $bank_transfer_1->setting_bank_transfer_bank_name }}</a>
                                                                                @else
                                                                                    <a class="nav-link bank_transfer_tab" id="v-pills-tab-{{ $bank_transfer_1->id }}-{{ $plan->id }}" data-toggle="pill" href="#v-pills-{{ $bank_transfer_1->id }}-{{ $plan->id }}" role="tab" aria-controls="v-pills-{{ $bank_transfer_1->id }}-{{ $plan->id }}" aria-selected="false" data-input-id="invoice_bank_transfer_bank_name_{{ $plan->id }}">{{ $bank_transfer_1->setting_bank_transfer_bank_name }}</a>
                                                                                @endif

                                                                            @endforeach
                                                                        </div>
                                                                        <hr>
                                                                        <div class="tab-content pt-2 pb-2" id="v-pills-tabContent">
                                                                            @foreach($all_setting_bank_transfers as $key_2 => $bank_transfer_2)
                                                                                @if($key_2 == 0)
                                                                                    <div class="tab-pane fade show active" id="v-pills-{{ $bank_transfer_2->id }}-{{ $plan->id }}" role="tabpanel" aria-labelledby="v-pills-tab-{{ $bank_transfer_2->id }}-{{ $plan->id }}">
                                                                                        <span class="text-gray-800">{{ __('bank_transfer.bank-account-info') }}:</span><br>
                                                                                        {!! clean(nl2br($bank_transfer_2->setting_bank_transfer_bank_account_info), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}
                                                                                    </div>
                                                                                @else
                                                                                    <div class="tab-pane fade" id="v-pills-{{ $bank_transfer_2->id }}-{{ $plan->id }}" role="tabpanel" aria-labelledby="v-pills-tab-{{ $bank_transfer_2->id }}-{{ $plan->id }}">
                                                                                        <span class="text-gray-800">{{ __('bank_transfer.bank-account-info') }}:</span><br>
                                                                                        {!! clean(nl2br($bank_transfer_2->setting_bank_transfer_bank_account_info), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                        @error('invoice_bank_transfer_bank_name')
                                                                        <span class="invalid-tooltip">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                                        @enderror
                                                                        <hr>
                                                                    </div>
                                                                </div>

                                                                <div class="row form-group">
                                                                    <div class="col-md-12 text-left">
                                                                        <label class="text-black" for="invoice_bank_transfer_detail">{{ __('bank_transfer.transaction-detail') }}</label>
                                                                        <textarea rows="4" id="invoice_bank_transfer_detail" type="text" class="form-control @error('invoice_bank_transfer_detail') is-invalid @enderror" name="invoice_bank_transfer_detail">{{ old('invoice_bank_transfer_detail') }}</textarea>
                                                                        <small class="text-muted">
                                                                            {{ __('bank_transfer.transaction-detail-help') }}
                                                                        </small>
                                                                        @error('invoice_bank_transfer_detail')
                                                                        <span class="invalid-tooltip">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                                                                <button type="submit" class="btn btn-success">{{ __('bank_transfer.submit') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($setting_site_paypal_enable == \App\Setting::SITE_PAYMENT_PAYPAL_ENABLE)
                                            <form method="GET" action="{{ route('user.paypal.checkout.do', ['plan_id'=>$plan->id, 'subscription_id'=>$subscription->id]) }}" class="">
                                                @csrf
                                                <div class="row form-group justify-content-between">
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-sm btn-success btn-block text-white" {{ $subscription->plan->plan_type == \App\Plan::PLAN_TYPE_PAID ? 'disabled' : '' }}>
                                                            {{ __('payment.pay-paypal') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif

                                        @if($setting_site_razorpay_enable == \App\Setting::SITE_PAYMENT_RAZORPAY_ENABLE)
                                            <form method="POST" action="{{ route('user.razorpay.checkout.do', ['plan_id'=>$plan->id, 'subscription_id'=>$subscription->id]) }}" class="">
                                                @csrf
                                                <div class="row form-group justify-content-between">
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-sm btn-success btn-block text-white" {{ $subscription->plan->plan_type == \App\Plan::PLAN_TYPE_PAID ? 'disabled' : '' }}>
                                                            {{ __('payment.pay-razorpay') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif

                                        @if($setting_site_stripe_enable == \App\Setting::SITE_PAYMENT_STRIPE_ENABLE)
                                            <form method="POST" action="{{ route('user.stripe.checkout.do', ['plan_id'=>$plan->id, 'subscription_id'=>$subscription->id]) }}" class="">
                                                @csrf
                                                <div class="row form-group justify-content-between">
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-sm btn-success btn-block text-white" {{ $subscription->plan->plan_type == \App\Plan::PLAN_TYPE_PAID ? 'disabled' : '' }}>
                                                            {{ __('stripe.pay-stripe') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif

                                        @if($setting_site_payumoney_enable == \App\Setting::SITE_PAYMENT_PAYUMONEY_ENABLE)
                                            <form method="POST" action="{{ route('user.payumoney.checkout.do', ['plan_id'=>$plan->id, 'subscription_id'=>$subscription->id]) }}" class="">
                                                @csrf
                                                <div class="row form-group justify-content-between">
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-sm btn-success btn-block text-white" {{ $subscription->plan->plan_type == \App\Plan::PLAN_TYPE_PAID ? 'disabled' : '' }}>
                                                            {{ __('payumoney.pay-payumoney') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif

                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            "use strict";

            @if($setting_site_bank_transfer_enable == \App\Setting::SITE_PAYMENT_BANK_TRANSFER_ENABLE)

            $(".bank_transfer_tab").on('shown.bs.tab', function (e) {
                e.target // newly activated tab
                e.relatedTarget // previous active tab

                var data_input_id = $(e.target).attr("data-input-id");

                $("#" + data_input_id).val(e.target.text);
            });

            @endif

        });
    </script>
@endsection

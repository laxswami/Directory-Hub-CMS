@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('bank_transfer.view-pending-invoice') . ' ' . $invoice->invoice_num }}</h1>
            <p class="mb-4">{{ __('bank_transfer.view-pending-invoice-description') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.settings.payment.bank-transfer.pending.index') }}" class="btn btn-info btn-icon-split">
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
            <div class="row">
                <div class="col-12">
                    <span class="text-gray-800 text-lg">{{ __('bank_transfer.transaction-status') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @if($invoice->invoice_status == \App\Invoice::INVOICE_STATUS_PENDING)
                        <span class="text-warning">{{ __('bank_transfer.pending') }}</span>
                    @elseif($invoice->invoice_status == \App\Invoice::INVOICE_STATUS_REJECT)
                        <span class="text-danger">{{ __('bank_transfer.reject') }}</span>
                    @elseif($invoice->invoice_status == \App\Invoice::INVOICE_STATUS_PAID)
                        <span class="text-success">{{ __('bank_transfer.paid') }}</span>
                    @endif
                </div>
            </div>

            <div class="row pt-3">
                <div class="col-12">
                    <span class="text-gray-800 text-lg">{{ __('backend.sidebar.plan') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <span>{{ $plan->plan_name }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <span>{{ $plan->plan_max_featured_listing . ' ' . __('bank_transfer.max-featured-listing') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <span>{{ __('backend.plan.price') . ': ' . strval($plan->plan_price) }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @if($plan->plan_period == \App\Plan::PLAN_LIFETIME)
                        <span>{{ __('bank_transfer.yearly-billing') }}</span>
                    @elseif($plan->plan_period == \App\Plan::PLAN_MONTHLY)
                        <span>{{ __('bank_transfer.monthly-billing') }}</span>
                    @elseif($plan->plan_period == \App\Plan::PLAN_QUARTERLY)
                        <span>{{ __('bank_transfer.quarterly-billing') }}</span>
                    @elseif($plan->plan_period == \App\Plan::PLAN_YEARLY)
                        <span>{{ __('bank_transfer.lifetime-billing') }}</span>
                    @endif
                </div>
            </div>

            <div class="row pt-3">
                <div class="col-12">
                    <span class="text-gray-800 text-lg">{{ __('bank_transfer.transaction-detail') }}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <span>{{ __('bank_transfer.bank-name') . ': ' . $invoice->invoice_bank_transfer_bank_name }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <span>{{ __('bank_transfer.transaction-detail') . ': ' . $invoice->invoice_bank_transfer_detail }}</span>
                </div>
            </div>

            @if($invoice->invoice_status == \App\Invoice::INVOICE_STATUS_PENDING || $invoice->invoice_status == \App\Invoice::INVOICE_STATUS_REJECT)
                <div class="row justify-content-between pt-3">
                    <div class="col-8">
                        <form action="{{ route('admin.settings.payment.bank-transfer.pending.approve', ['invoice' => $invoice->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm text-white">
                                {{ __('bank_transfer.approve-transaction') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-4 text-right">
                        <form action="{{ route('admin.settings.payment.bank-transfer.pending.reject', ['invoice' => $invoice->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm text-white">
                                {{ __('bank_transfer.reject-transaction') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection

@section('scripts')
@endsection

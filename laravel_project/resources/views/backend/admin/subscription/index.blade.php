@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.subscription.subscription') }}</h1>
            <p class="mb-4">{{ __('backend.subscription.subscription-desc') }}</p>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{ __('backend.subscription.id') }}</th>
                                <th>{{ __('backend.subscription.type') }}</th>
                                <th>{{ __('backend.subscription.price') }}</th>
                                <th>{{ __('backend.subscription.cycle') }}</th>
                                <th>{{ __('backend.subscription.name') }}</th>
                                <th>{{ __('backend.subscription.start') }}</th>
                                <th>{{ __('backend.subscription.end') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('backend.subscription.id') }}</th>
                                <th>{{ __('backend.subscription.type') }}</th>
                                <th>{{ __('backend.subscription.price') }}</th>
                                <th>{{ __('backend.subscription.cycle') }}</th>
                                <th>{{ __('backend.subscription.name') }}</th>
                                <th>{{ __('backend.subscription.start') }}</th>
                                <th>{{ __('backend.subscription.end') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_subscription as $key => $subscription)
                                <tr>
                                    <td>{{ $subscription->id }}</td>
                                    <td>
                                        @if($subscription->plan->plan_type == \App\Plan::PLAN_TYPE_FREE)
                                            {{ __('backend.plan.free') }}
                                        @else
                                            {{ __('backend.plan.paid') }}
                                        @endif
                                    </td>
                                    <td>{{ $subscription->plan->plan_price }}</td>
                                    <td>
                                        @if($subscription->plan->plan_period == \App\Plan::PLAN_LIFETIME)
                                            {{ __('backend.plan.lifetime') }}
                                        @elseif($subscription->plan->plan_period == \App\Plan::PLAN_MONTHLY)
                                            {{ __('backend.plan.monthly') }}
                                        @elseif($subscription->plan->plan_period == \App\Plan::PLAN_QUARTERLY)
                                            {{ __('backend.plan.quarterly') }}
                                        @elseif($subscription->plan->plan_period == \App\Plan::PLAN_YEARLY)
                                            {{ __('backend.plan.yearly') }}
                                        @endif
                                    </td>
                                    <td>{{ $subscription->user->name }}</td>
                                    <td>{{ $subscription->subscription_start_date }}</td>
                                    <td>{{ $subscription->subscription_end_date }}</td>
                                    <td>
                                        <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="btn btn-primary btn-circle">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function() {

            "use strict";

            $('#dataTable').DataTable();
        });
    </script>
@endsection

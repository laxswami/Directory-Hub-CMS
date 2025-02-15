@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.plan.plan') }}</h1>
            <p class="mb-4">{{ __('backend.plan.plan-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.plans.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.plan.add-plan') }}</span>
            </a>
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
                                <th>{{ __('backend.plan.type') }}</th>
                                <th>{{ __('backend.plan.name') }}</th>
                                <th>{{ __('theme_directory_hub.plan.free-listing-cap') }}</th>
                                <th>{{ __('backend.plan.featured-listing') }}</th>
                                <th>{{ __('backend.plan.features') }}</th>
                                <th>{{ __('backend.plan.period') }}</th>
                                <th>{{ __('backend.plan.price') }}</th>
                                <th>{{ __('backend.plan.status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('backend.plan.type') }}</th>
                                <th>{{ __('backend.plan.name') }}</th>
                                <th>{{ __('theme_directory_hub.plan.free-listing-cap') }}</th>
                                <th>{{ __('backend.plan.featured-listing') }}</th>
                                <th>{{ __('backend.plan.features') }}</th>
                                <th>{{ __('backend.plan.period') }}</th>
                                <th>{{ __('backend.plan.price') }}</th>
                                <th>{{ __('backend.plan.status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_plans as $key => $plan)
                                <tr>
                                    <td>
                                        @if($plan->plan_type == \App\Plan::PLAN_TYPE_FREE)
                                            {{ __('backend.plan.free') }}
                                        @else
                                            {{ __('backend.plan.paid') }}
                                        @endif
                                    </td>
                                    <td>{{ $plan->plan_name }}</td>
                                    <td>
                                        @if(is_null($plan->plan_max_free_listing))
                                            {{ __('backend.plan.unlimited') }}
                                        @else
                                            {{ $plan->plan_max_free_listing }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(is_null($plan->plan_max_featured_listing))
                                            {{ __('backend.plan.unlimited') }}
                                        @else
                                            {{ $plan->plan_max_featured_listing }}
                                        @endif
                                    </td>
                                    <td>{{ $plan->plan_features }}</td>
                                    <td>
                                        @if($plan->plan_period == \App\Plan::PLAN_LIFETIME)
                                            {{ __('backend.plan.lifetime') }}
                                        @elseif($plan->plan_period == \App\Plan::PLAN_MONTHLY)
                                            {{ __('backend.plan.monthly') }}
                                        @elseif($plan->plan_period == \App\Plan::PLAN_QUARTERLY)
                                            {{ __('backend.plan.quarterly') }}
                                        @elseif($plan->plan_period == \App\Plan::PLAN_YEARLY)
                                            {{ __('backend.plan.yearly') }}
                                        @endif
                                    </td>
                                    <td>{{ $plan->plan_price }}</td>
                                    <td>
                                        @if($plan->plan_status == \App\Plan::PLAN_ENABLED)
                                            <span class="bg-success text-white pl-2 pr-2 pt-1 pb-1">{{ __('backend.plan.enabled') }}</span>
                                        @else
                                            <span class="bg-danger text-white pl-2 pr-2 pt-1 pb-1">{{ __('backend.plan.disabled') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.plans.edit', $plan->id) }}" class="btn btn-primary btn-circle">
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

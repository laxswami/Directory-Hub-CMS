@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.plan.edit-plan') }}</h1>
            <p class="mb-4">{{ __('backend.plan.edit-plan-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.plans.index') }}" class="btn btn-info btn-icon-split">
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

            <div class="row pb-3">
                <div class="col-12">
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ __('theme_directory_hub.plan.edit-plan-warning') }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-8 col-lg-6">
                    <form method="POST" action="{{ route('admin.plans.update', $plan->id) }}" class="">
                        @csrf
                        @method('PUT')
                        <div class="row form-group">
                            <div class="col-md-12">
                                @if($plan->plan_type == \App\Plan::PLAN_TYPE_FREE)
                                    <span class="bg-info text-white pl-1 pr-1 rounded">{{ __('backend.plan.free-plan') }}</span>
                                @else
                                    <span class="bg-info text-white pl-1 pr-1 rounded">{{ __('backend.plan.paid-plan') }}</span>
                                @endif

                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="plan_name" class="text-black">{{ __('backend.plan.name') }}</label>
                                <input id="plan_name" type="text" class="form-control @error('plan_name') is-invalid @enderror" name="plan_name" value="{{ old('plan_name') ? old('plan_name') : $plan->plan_name }}" autofocus>
                                @error('plan_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="plan_features">{{ __('backend.plan.features') }}</label>
                                <textarea rows="6" id="plan_features" type="text" class="form-control @error('plan_features') is-invalid @enderror" name="plan_features">{{ old('plan_features') ? old('plan_features') : $plan->plan_features }}</textarea>
                                @error('plan_features')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if($plan->plan_type == \App\Plan::PLAN_TYPE_PAID)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label for="plan_price" class="text-black">{{ __('backend.plan.price') }}</label>
                                    <input id="plan_price" type="text" class="form-control @error('plan_price') is-invalid @enderror" name="plan_price" value="{{ old('plan_price') ? old('plan_price') : $plan->plan_price }}">
                                    @error('plan_price')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        @if($plan->plan_type == \App\Plan::PLAN_TYPE_PAID)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label for="plan_period" class="text-black">{{ __('backend.plan.billing-period') }}</label>

                                    <select class="custom-select" name="plan_period">
                                        <option value="{{ \App\Plan::PLAN_MONTHLY }}" {{ (old('plan_period') ? old('plan_period') : $plan->plan_period) == \App\Plan::PLAN_MONTHLY ? 'selected' : '' }}>
                                            {{ __('backend.plan.monthly') }}
                                        </option>
                                        <option value="{{ \App\Plan::PLAN_QUARTERLY }}" {{ (old('plan_period') ? old('plan_period') : $plan->plan_period) == \App\Plan::PLAN_QUARTERLY ? 'selected' : '' }}>
                                            {{ __('backend.plan.quarterly') }}
                                        </option>
                                        <option value="{{ \App\Plan::PLAN_YEARLY }}" {{ (old('plan_period') ? old('plan_period') : $plan->plan_period) == \App\Plan::PLAN_YEARLY ? 'selected' : '' }}>
                                            {{ __('backend.plan.yearly') }}
                                        </option>
                                    </select>
                                    @error('plan_period')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="plan_max_free_listing" class="text-black">{{ __('theme_directory_hub.plan.max-free-listing') }}</label>
                                <input id="plan_max_free_listing" type="text" class="form-control @error('plan_max_free_listing') is-invalid @enderror" name="plan_max_free_listing" value="{{ old('plan_max_free_listing') ? old('plan_max_free_listing') : $plan->plan_max_free_listing }}" aria-describedby="plan_max_free_listingHelpBlock">
                                <small id="plan_max_free_listingHelpBlock" class="form-text text-muted">
                                    {{ __('theme_directory_hub.plan.max-free-listing-help') }}
                                </small>
                                @error('plan_max_free_listing')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="plan_max_featured_listing" class="text-black">{{ __('backend.plan.maximum-featured-listing') }}</label>
                                <input id="plan_max_featured_listing" type="text" class="form-control @error('plan_max_featured_listing') is-invalid @enderror" name="plan_max_featured_listing" value="{{ old('plan_max_featured_listing') ? old('plan_max_featured_listing') : $plan->plan_max_featured_listing }}" aria-describedby="plan_max_featured_listingHelpBlock">
                                <small id="plan_max_featured_listingHelpBlock" class="form-text text-muted">
                                    {{ __('theme_directory_hub.plan.max-featured-listing-help') }}
                                </small>
                                @error('plan_max_featured_listing')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        @if($plan->plan_type == \App\Plan::PLAN_TYPE_PAID)
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="plan_status" class="text-black">{{ __('backend.plan.status') }}</label>

                                <select class="custom-select" name="plan_status">
                                    <option value="{{ \App\Plan::PLAN_ENABLED }}" {{ (old('plan_status') ? old('plan_status') : $plan->plan_status) == \App\Plan::PLAN_ENABLED ? 'selected' : '' }}>
                                        {{ __('backend.plan.enabled') }}
                                    </option>
                                    <option value="{{ \App\Plan::PLAN_DISABLED }}" {{ (old('plan_status') ? old('plan_status') : $plan->plan_status) == \App\Plan::PLAN_DISABLED ? 'selected' : '' }}>
                                        {{ __('backend.plan.disabled') }}
                                    </option>
                                </select>
                                @error('plan_status')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @endif

                        <div class="row form-group justify-content-between">
                            <div class="col-8">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.update') }}
                                </button>
                            </div>
                            <div class="col-4 text-right">
                                @if($plan->plan_type == \App\Plan::PLAN_TYPE_PAID)
                                <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                                    {{ __('backend.shared.delete') }}
                                </a>
                                @endif
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($plan->plan_type == \App\Plan::PLAN_TYPE_PAID)
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('backend.shared.delete-message', ['record_type' => __('backend.shared.plan'), 'record_name' => $plan->plan_name]) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

@endsection

@section('scripts')
@endsection

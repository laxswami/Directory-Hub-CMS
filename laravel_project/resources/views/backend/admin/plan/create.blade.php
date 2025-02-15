@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.plan.add-plan') }}</h1>
            <p class="mb-4">{{ __('backend.plan.add-plan-desc') }}</p>
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
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6">
                    <form method="POST" action="{{ route('admin.plans.store') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-md-12">
                                <span>{{ __('backend.plan.plan-type') }}: </span>
                                <span class="text-gray-800">{{ __('backend.plan.paid-plan') }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="plan_name" class="text-black">{{ __('backend.plan.name') }}</label>
                                <input id="plan_name" type="text" class="form-control @error('plan_name') is-invalid @enderror" name="plan_name" value="{{ old('plan_name') }}" autofocus>
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
                                <textarea rows="6" id="plan_features" type="text" class="form-control @error('plan_features') is-invalid @enderror" name="plan_features">{{ old('plan_features') }}</textarea>
                                @error('plan_features')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="plan_price" class="text-black">{{ __('backend.plan.price') }}</label>
                                <input id="plan_price" type="text" class="form-control @error('plan_price') is-invalid @enderror" name="plan_price" value="{{ old('plan_price') }}">
                                @error('plan_price')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="plan_period" class="text-black">{{ __('backend.plan.billing-period') }}</label>

                                <select class="custom-select" name="plan_period">
                                    <option value="{{ \App\Plan::PLAN_MONTHLY }}" {{ old('plan_period') == \App\Plan::PLAN_MONTHLY ? 'selected' : '' }}>
                                        {{ __('backend.plan.monthly') }}
                                    </option>
                                    <option value="{{ \App\Plan::PLAN_QUARTERLY }}" {{ old('plan_period') == \App\Plan::PLAN_QUARTERLY ? 'selected' : '' }}>
                                        {{ __('backend.plan.quarterly') }}
                                    </option>
                                    <option value="{{ \App\Plan::PLAN_YEARLY }}" {{ old('plan_period') == \App\Plan::PLAN_YEARLY ? 'selected' : '' }}>
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

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="plan_max_free_listing" class="text-black">{{ __('theme_directory_hub.plan.max-free-listing') }}</label>
                                <input id="plan_max_free_listing" type="text" class="form-control @error('plan_max_free_listing') is-invalid @enderror" name="plan_max_free_listing" value="{{ old('plan_max_free_listing') }}" aria-describedby="plan_max_free_listingHelpBlock">
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
                                <input id="plan_max_featured_listing" type="text" class="form-control @error('plan_max_featured_listing') is-invalid @enderror" name="plan_max_featured_listing" value="{{ old('plan_max_featured_listing') }}" aria-describedby="plan_max_featured_listingHelpBlock">
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

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="plan_status" class="text-black">{{ __('backend.plan.status') }}</label>

                                <select class="custom-select" name="plan_status">
                                    <option value="{{ \App\Plan::PLAN_ENABLED }}" {{ old('plan_status') == \App\Plan::PLAN_ENABLED ? 'selected' : '' }}>
                                        {{ __('backend.plan.enabled') }}
                                    </option>
                                    <option value="{{ \App\Plan::PLAN_DISABLED }}" {{ old('plan_status') == \App\Plan::PLAN_DISABLED ? 'selected' : '' }}>
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


                        <div class="row form-group justify-content-between">
                            <div class="col-8">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.create') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
@endsection

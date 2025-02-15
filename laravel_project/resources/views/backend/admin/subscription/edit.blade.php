@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.subscription.edit-subscription') }}</h1>
            <p class="mb-4">{{ __('backend.subscription.edit-subscription-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-info btn-icon-split">
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
                    <form method="POST" action="{{ route('admin.subscriptions.update', $subscription->id) }}" class="">
                        @csrf
                        @method('PUT')
                        <div class="row form-group">
                            <div class="col-md-12">
                                <span>{{ __('backend.subscription.subscription-for') }}: </span>
                                <span class="text-gray-800">{{ $subscription->user->name }}</span><br/>
                                <span>{{ __('backend.subscription.started-at') }}: </span>
                                <span class="text-gray-800">{{ $subscription->subscription_start_date }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="plan_id" class="text-black">{{ __('backend.plan.plan') }}</label>

                                <select class="custom-select" name="plan_id">

                                    @foreach($all_plans as $key => $plan)
                                        <option value="{{ $plan->id }}" {{ (old('plan_id') ? old('plan_id') : $subscription->plan_id) == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->plan_type == \App\Plan::PLAN_TYPE_FREE ? __('theme_directory_hub.plan.free-plan') : __('theme_directory_hub.plan.paid-plan') }} |
                                            {{ $plan->plan_name }}
                                            |
                                            @if($plan->plan_period == \App\Plan::PLAN_LIFETIME)
                                                {{ $plan->plan_price }}/{{ __('backend.plan.lifetime') }}
                                            @elseif($plan->plan_period == \App\Plan::PLAN_MONTHLY)
                                                {{ $plan->plan_price }}/{{ __('backend.plan.monthly') }}
                                            @elseif($plan->plan_period == \App\Plan::PLAN_QUARTERLY)
                                                {{ $plan->plan_price }}/{{ __('backend.plan.quarterly') }}
                                            @elseif($plan->plan_period == \App\Plan::PLAN_YEARLY)
                                                {{ $plan->plan_price }}/{{ __('backend.plan.yearly') }}
                                            @endif
                                            |
                                            {{ is_null($plan->plan_max_free_listing) ? __('theme_directory_hub.plan.unlimited') . ' ' . __('theme_directory_hub.plan.free-listing') : $plan->plan_max_free_listing . ' ' . __('theme_directory_hub.plan.free-listing') }}
                                            |
                                            {{ is_null($plan->plan_max_featured_listing) ? __('theme_directory_hub.plan.unlimited') . ' ' . __('theme_directory_hub.plan.featured-listing') : $plan->plan_max_featured_listing . ' ' . __('theme_directory_hub.plan.featured-listing') }}
                                        </option>
                                    @endforeach

                                </select>
                                @error('plan_id')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="subscription_end_date" class="text-black">{{ __('backend.subscription.end-at') }}</label>
                                <input id="subscription_end_date" type="text" class="form-control @error('subscription_end_date') is-invalid @enderror" name="subscription_end_date" value="{{ old('subscription_end_date') ? old('subscription_end_date') : $subscription->subscription_end_date }}" aria-describedby="subscription_end_dateHelpBlock">
                                <small id="subscription_end_dateHelpBlock" class="form-text text-muted">
                                    {{ __('theme_directory_hub.plan.subscription-edn-date-help') }}
                                </small>
                                @error('subscription_end_date')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group justify-content-between">
                            <div class="col-8">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.update') }}
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

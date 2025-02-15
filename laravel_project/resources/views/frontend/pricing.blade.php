@extends('frontend.layouts.app')

@section('styles')
@endsection

@section('content')

    @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_DEFAULT)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( {{ asset('frontend/images/placeholder/header-inner.webp') }});">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: {{ $site_innerpage_header_background_color }};">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( {{ Storage::disk('public')->url('customization/' . $site_innerpage_header_background_image) }});">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: #333333;">
    @endif

        @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            <div data-youtube="{{ $site_innerpage_header_background_youtube_video }}"></div>
        @endif

        <div class="container">
            <div class="row align-items-center justify-content-center text-center">

                <div class="col-md-10" data-aos="fade-up" data-aos-delay="400">


                    <div class="row justify-content-center mt-5">
                        <div class="col-md-8 text-center">
                            <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ __('theme_directory_hub.pricing.head-title') }}</h1>
                            <p class="mb-0" style="color: {{ $site_innerpage_header_paragraph_font_color }};">{{ __('theme_directory_hub.pricing.head-description') }}</p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">

            @if(!empty($login_user))
            <div class="row justify-content-center">
                <div class="col-10 col-md-12">
                    <div class="alert alert-info" role="alert">
                        @if($login_user->isAdmin())
                           {{ __('theme_directory_hub.pricing.info-admin') }}
                        @else
                            @if($login_user->hasPaidSubscription())
                                {{ __('theme_directory_hub.pricing.info-user-paid', ['site_name' => $site_name]) }}
                            @else
                                {{ __('theme_directory_hub.pricing.info-user-free', ['site_name' => $site_name]) }}
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="row justify-content-center">
                @foreach($plans as $plans_key => $plan)
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

                                    @if($plan->plan_type == \App\Plan::PLAN_TYPE_FREE)
                                        @if(empty($login_user))
                                            <a class="btn btn-block btn-primary text-white rounded" href="{{ route('user.items.create') }}">
                                                <i class="fas fa-plus mr-1"></i>
                                                {{ __('frontend.header.list-business') }}
                                            </a>
                                        @else
                                            @if($login_user->isAdmin())
                                                <a class="btn btn-block btn-primary text-white rounded" href="{{ route('admin.plans.index') }}">
                                                    <i class="fas fa-tasks"></i>
                                                    {{ __('theme_directory_hub.pricing.manage-pricing') }}
                                                </a>
                                            @else
                                                @if(!$login_user->hasPaidSubscription())
                                                    <a class="btn btn-block btn-primary text-white rounded" href="{{ route('user.items.create') }}">
                                                        <i class="fas fa-plus mr-1"></i>
                                                        {{ __('frontend.header.list-business') }}
                                                    </a>
                                                @endif
                                            @endif
                                        @endif
                                    @else

                                        @if(empty($login_user))
                                            <a class="btn btn-block btn-primary text-white rounded" href="{{ route('user.subscriptions.index') }}">
                                                <i class="fas fa-plus mr-1"></i>
                                                {{ __('theme_directory_hub.pricing.get-started') }}
                                            </a>
                                        @else
                                            @if($login_user->isAdmin())
                                                <a class="btn btn-block btn-primary text-white rounded" href="{{ route('admin.plans.index') }}">
                                                    <i class="fas fa-tasks"></i>
                                                    {{ __('theme_directory_hub.pricing.manage-pricing') }}
                                                </a>
                                            @else

                                                @if($login_user->hasPaidSubscription())

                                                    @if($login_user->subscription->plan->id == $plan->id)
                                                        <a class="btn btn-block btn-primary text-white rounded" href="{{ route('user.items.create') }}">
                                                            <i class="fas fa-plus mr-1"></i>
                                                            {{ __('frontend.header.list-business') }}
                                                        </a>
                                                    @endif

                                                @else
                                                    <a class="btn btn-block btn-primary rounded text-white" href="{{ route('user.subscriptions.edit', ['subscription' => $login_user->subscription->id]) }}">
                                                        <i class="fas fa-shopping-cart"></i>
                                                        {{ __('theme_directory_hub.pricing.upgrade') }}
                                                    </a>
                                                @endif

                                            @endif
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

    @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <!-- Youtube Background for Header -->
            <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif
    <script>

        $(document).ready(function(){

            "use strict";

            @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            /**
             * Start Initial Youtube Background
             */
            $("[data-youtube]").youtube_background();
            /**
             * End Initial Youtube Background
             */
            @endif

        });

    </script>
@endsection

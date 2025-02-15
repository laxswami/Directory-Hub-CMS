@extends('frontend.layouts.app')

@section('styles')
    <!-- Start Google reCAPTCHA version 2 -->
    @if($site_global_settings->setting_site_recaptcha_login_enable == \App\Setting::SITE_RECAPTCHA_LOGIN_ENABLE)
        {!! htmlScriptTagJsApi(['lang' => empty(\Illuminate\Support\Facades\App::getLocale()) ? 'en' : \Illuminate\Support\Facades\App::getLocale()]) !!}
    @endif
    <!-- End Google reCAPTCHA version 2 -->
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
                        <div class="col-md-10 text-center">
                            <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ __('auth.login') }}</h1>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="site-section bg-light">
        <div class="container">

            @include('frontend.partials.alert')

            @if(is_demo_mode())
            <div class="row justify-content-center">
                <div class="col-12 col-md-7">
                    <div class="alert alert-info alert-dismissible" role="alert">
                        <p><strong>{{ __('demo_login.demo-login-credentials') }}</strong></p>
                        <hr>

                        <div class="row">
                            <div class="col-2"><u>{{ __('demo_login.admin-account') }}</u></div>
                            <div class="col-7 text-right">{{ \App\Setting::DEMO_ADMIN_LOGIN_EMAIL . " / " . \App\Setting::DEMO_ADMIN_LOGIN_PASSWORD }}</div>
                            <div class="col-3 text-right">
                                <a class="btn btn-sm btn-primary rounded text-white" id="copy-admin-login-button">
                                    <i class="fas fa-copy"></i>
                                    {{ __('demo_login.copy-to-login') }}
                                </a>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-2"><u>{{ __('demo_login.user-account') }}</u></div>
                            <div class="col-7 text-right">{{ \App\Setting::DEMO_USER_LOGIN_EMAIL . " / " . \App\Setting::DEMO_USER_LOGIN_PASSWORD }}</div>
                            <div class="col-3 text-right">
                                <a class="btn btn-sm btn-primary rounded text-white" id="copy-user-login-button">
                                    <i class="fas fa-copy"></i>
                                    {{ __('demo_login.copy-to-login') }}
                                </a>
                            </div>
                        </div>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-md-7 mb-5">

                    <form method="POST" action="{{ route('login') }}" class="p-5 bg-white">
                        @csrf
                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="email">{{ __('auth.email-addr') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="subject">{{ __('auth.password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('auth.remember-me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Start Google reCAPTCHA version 2 -->
                        @if($site_global_settings->setting_site_recaptcha_login_enable == \App\Setting::SITE_RECAPTCHA_LOGIN_ENABLE)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    {!! htmlFormSnippet() !!}
                                    @error('g-recaptcha-response')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <!-- End Google reCAPTCHA version 2 -->

                        <div class="row form-group">
                            <div class="col-12">
                                <p>{{ __('auth.no-account-yet') }}? <a href="{{ route('register') }}">{{ __('auth.register') }}</a></p>
                            </div>
                        </div>


                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary py-2 px-4 text-white rounded">
                                    {{ __('auth.login') }}
                                </button>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('auth.forgot-password') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if($social_login_facebook || $social_login_google || $social_login_twitter || $social_login_linkedin || $social_login_github)
                            <div class="row mt-4 align-items-center">
                                <div class="col-md-5">
                                    <hr>
                                </div>
                                <div class="col-md-2 pl-0 pr-0 text-center">
                                    <span>{{ __('social_login.frontend.or') }}</span>
                                </div>
                                <div class="col-md-5">
                                    <hr>
                                </div>
                            </div>
                            <div class="row align-items-center mb-4 mt-2">
                                <div class="col-md-12 text-center">
                                    <span>{{ __('social_login.frontend.sign-in-with') }}</span>
                                </div>
                            </div>
                        @endif

                        @if($social_login_facebook)
                        <div class="row form-group">
                            <div class="col-md-12">
                                <a class="btn btn-facebook btn-block text-white rounded" href="{{ route('auth.login.facebook') }}">
                                    <i class="fab fa-facebook-f pr-2"></i>
                                    {{ __('social_login.frontend.sign-in-facebook') }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($social_login_google)
                        <div class="row form-group">
                            <div class="col-md-12">
                                <a class="btn btn-google btn-block text-white rounded" href="{{ route('auth.login.google') }}">
                                    <i class="fab fa-google pr-2"></i>
                                    {{ __('social_login.frontend.sign-in-google') }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($social_login_twitter)
                        <div class="row form-group">
                                <div class="col-md-12">
                                    <a class="btn btn-twitter btn-block text-white rounded" href="{{ route('auth.login.twitter') }}">
                                        <i class="fab fa-twitter pr-2"></i>
                                        {{ __('social_login.frontend.sign-in-twitter') }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($social_login_linkedin)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <a class="btn btn-linkedin btn-block text-white rounded" href="{{ route('auth.login.linkedin') }}">
                                        <i class="fab fa-linkedin-in pr-2"></i>
                                        {{ __('social_login.frontend.sign-in-linkedin') }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($social_login_github)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <a class="btn btn-github btn-block text-white rounded" href="{{ route('auth.login.github') }}">
                                        <i class="fab fa-github pr-2"></i>
                                        {{ __('social_login.frontend.sign-in-github') }}
                                    </a>
                                </div>
                            </div>
                        @endif

                    </form>
                </div>

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

            @if(is_demo_mode())
            $('#copy-admin-login-button').on('click', function(){
                $('#email').val("{{ \App\Setting::DEMO_ADMIN_LOGIN_EMAIL }}");
                $('#password').val("{{ \App\Setting::DEMO_ADMIN_LOGIN_PASSWORD }}");
            });

            $('#copy-user-login-button').on('click', function(){
                $('#email').val("{{ \App\Setting::DEMO_USER_LOGIN_EMAIL }}");
                $('#password').val("{{ \App\Setting::DEMO_USER_LOGIN_PASSWORD }}");
            });
            @endif
        });

    </script>

@endsection

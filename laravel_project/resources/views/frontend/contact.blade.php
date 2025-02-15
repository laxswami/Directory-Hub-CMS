@extends('frontend.layouts.app')

@section('styles')
    <!-- Start Google reCAPTCHA version 2 -->
    @if($site_global_settings->setting_site_recaptcha_contact_enable == \App\Setting::SITE_RECAPTCHA_CONTACT_ENABLE)
        {!! htmlScriptTagJsApi(['lang' => empty($site_global_settings->setting_site_language) ? 'en' : $site_global_settings->setting_site_language]) !!}
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
                        <div class="col-md-8 text-center">
                            <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ __('frontend.contact.title') }}</h1>
                            <p class="mb-0" style="color: {{ $site_innerpage_header_paragraph_font_color }};">{{ __('frontend.contact.description') }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="site-section bg-light">
        <div class="container">

            @include('frontend.partials.alert')

            <div class="row">
                <div class="col-md-7 mb-5">

                    <form action="{{ route('page.contact.do') }}" class="p-5 bg-white" method="POST">
                        @csrf

                        <div class="row form-group">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="text-black" for="first_name">{{ __('frontend.contact.first-name') }}</label>
                                <input name="first_name" type="text" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}">
                                @error('first_name')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="text-black" for="last_name">{{ __('frontend.contact.last-name') }}</label>
                                <input name="last_name" type="text" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}">
                                @error('last_name')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="email">{{ __('frontend.contact.email') }}</label>
                                <input name="email" type="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                @error('email')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="subject">{{ __('frontend.contact.subject') }}</label>
                                <input name="subject" type="text" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}">
                                @error('subject')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="message">{{ __('frontend.contact.message') }}</label>
                                <textarea name="message" id="message" cols="30" rows="7" class="form-control @error('message') is-invalid @enderror" placeholder="">{{ old('message') }}</textarea>
                                @error('message')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Start Google reCAPTCHA version 2 -->
                        @if($site_global_settings->setting_site_recaptcha_contact_enable == \App\Setting::SITE_RECAPTCHA_CONTACT_ENABLE)
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
                            <div class="col-md-12">
                                <input type="submit" value="{{ __('frontend.item.send-message') }}" class="btn btn-primary py-2 px-4 text-white">
                            </div>
                        </div>

                    </form>
                </div>
                <div class="col-md-5">

                    @if(!empty($site_global_settings->setting_site_address) || !empty($site_global_settings->setting_site_city) || !empty($site_global_settings->setting_site_state) || !empty($site_global_settings->setting_site_postal_code) || !empty($site_global_settings->setting_site_country) || !empty($site_global_settings->setting_site_phone) || !empty($site_global_settings->setting_site_email))
                    <div class="p-4 mb-3 bg-white">

                        @if(!empty($site_global_settings->setting_site_address) || !empty($site_global_settings->setting_site_city) || !empty($site_global_settings->setting_site_state) || !empty($site_global_settings->setting_site_postal_code) || !empty($site_global_settings->setting_site_country))
                        <p class="mb-0 font-weight-bold">{{ __('frontend.contact.address') }}</p>
                        <p class="mb-4">
                            @if(!empty($site_global_settings->setting_site_address))
                                {{ $site_global_settings->setting_site_address }}<br>
                            @endif

                            @if(!empty($site_global_settings->setting_site_city))
                                {{ $site_global_settings->setting_site_city . (!empty($site_global_settings->setting_site_state) ? ', ' : '') }}
                            @endif

                            @if(!empty($site_global_settings->setting_site_state))
                                {{ $site_global_settings->setting_site_state . ' ' }}
                            @endif

                            @if(!empty($site_global_settings->setting_site_postal_code))
                                {{ $site_global_settings->setting_site_postal_code }}<br>
                            @endif

                            @if(!empty($site_global_settings->setting_site_country))
                                {{ $site_global_settings->setting_site_country }}
                            @endif
                        </p>
                        @endif

                        @if(!empty($site_global_settings->setting_site_phone))
                        <p class="mb-0 font-weight-bold">{{ __('frontend.contact.phone') }}</p>
                        <p class="mb-4"><a href="#">{{ $site_global_settings->setting_site_phone }}</a></p>
                        @endif

                        @if(!empty($site_global_settings->setting_site_email))
                        <p class="mb-0 font-weight-bold">{{ __('frontend.contact.email-address') }}</p>
                        <p class="mb-0"><a href="#">{{ $site_global_settings->setting_site_email }}</a></p>
                        @endif

                    </div>
                    @endif

                    <div class="p-4 mb-3 bg-white">
                        <h3 class="h5 text-black mb-3">{{ __('frontend.contact.more-info') }}</h3>
                        <p>{{ $site_global_settings->setting_site_about }}</p>
                        @if($site_global_settings->setting_page_about_enable == \App\Setting::ABOUT_PAGE_ENABLED)
                        <p><a href="{{ route('page.about') }}" class="btn btn-primary px-4 py-2 text-white">{{ __('frontend.contact.learn-more') }}</a></p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    @if($all_faq->count() > 0)
    <div class="site-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-7 text-center border-primary">
                    <h2 class="font-weight-light text-primary">{{ __('frontend.contact.faq') }}</h2>
                    <p class="color-black-opacity-5"></p>
                </div>
            </div>


            <div class="row justify-content-center">
                <div class="col-8">

                    @foreach($all_faq as $key => $faq)
                        <div class="border p-3 rounded mb-2">
                            <a data-toggle="collapse" href="#collapse-{{ $faq->id }}" role="button" aria-expanded="false" aria-controls="collapse-{{ $faq->id }}" class="accordion-item h5 d-block mb-0">{{ $faq->faqs_question }}</a>

                            <div class="collapse" id="collapse-{{ $faq->id }}">
                                <div class="pt-2">
                                    <p class="mb-0">{{ $faq->faqs_answer }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </div>
    @endif

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

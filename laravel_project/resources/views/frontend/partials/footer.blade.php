<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-9 mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="footer-heading mb-4"><strong>{{ __('frontend.footer.about') }}</strong></h2>
                        <p>{!! clean(nl2br($site_global_settings->setting_site_about), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}</p>
                    </div>

                    <div class="col-md-3">
                        <h2 class="footer-heading mb-4"><strong>{{ __('frontend.footer.navigations') }}</strong></h2>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('page.pricing') }}">{{ __('theme_directory_hub.pricing.footer.pricing') }}</a></li>

                            @if($site_global_settings->setting_page_about_enable == \App\Setting::ABOUT_PAGE_ENABLED)
                            <li><a href="{{ route('page.about') }}">{{ __('frontend.footer.about-us') }}</a></li>
                            @endif
                            <li><a href="{{ route('page.contact') }}">{{ __('frontend.footer.contact-us') }}</a></li>
                            @if($site_global_settings->setting_page_terms_of_service_enable == \App\Setting::TERM_PAGE_ENABLED)
                                <li><a href="{{ route('page.terms-of-service') }}">{{ __('frontend.footer.terms-of-service') }}</a></li>
                            @endif
                            @if($site_global_settings->setting_page_privacy_policy_enable == \App\Setting::PRIVACY_PAGE_ENABLED)
                                <li><a href="{{ route('page.privacy-policy') }}">{{ __('frontend.footer.privacy-policy') }}</a></li>
                            @endif
                            @if($site_global_settings->setting_site_sitemap_show_in_footer == \App\Setting::SITE_SITEMAP_SHOW_IN_FOOTER)
                                <li><a href="{{ route('page.sitemap.index') }}">{{ __('sitemap.sitemap') }}</a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h2 class="footer-heading mb-4"><strong>{{ __('frontend.footer.follow-us') }}</strong></h2>
                        @foreach(\App\SocialMedia::orderBy('social_media_order')->get() as $key => $social_media)
                            <a href="{{ $social_media->social_media_link }}" class="pl-0 pr-3">
                                <i class="{{ $social_media->social_media_icon }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <h2 class="footer-heading mb-4"><strong>{{ __('frontend.footer.posts') }}</strong></h2>
                <ul class="list-unstyled">
                    @foreach(\Canvas\Post::published()->orderByDesc('published_at')->take(5)->get() as $key => $post)
                        <li><a href="{{ route('page.blog.show', $post->slug) }}">{{ $post->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="row pt-2 mt-5 pb-2">
            <div class="col-md-12">
                <div class="btn-group dropup">
                    <button class="btn btn-sm rounded dropdown-toggle btn-footer-dropdown" type="button" id="table_option_dropdown_country" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-globe"></i>
                        {{ $site_prefer_country_name }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="table_option_dropdown_country">
                        @foreach($site_available_countries as $site_available_countries_key => $country)
                            @if($country->country_status == \App\Country::COUNTRY_STATUS_ENABLE)
                            <a class="dropdown-item" href="{{ route('page.country.update', ['user_prefer_country_id' => $country->id]) }}">
                                {{ $country->country_name }}
                            </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row pt-2 pb-2">
            <div class="col-md-12">
                <div class="btn-group dropup">
                    <button class="btn btn-sm rounded dropdown-toggle btn-footer-dropdown" type="button" id="table_option_dropdown_locale" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-language"></i>
                        {{ __('prefer_languages.' . app()->getLocale()) }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="table_option_dropdown_locale">
                        @foreach(\App\Setting::LANGUAGES as $setting_languages_key => $language)
                            @if(\Illuminate\Support\Facades\Schema::hasTable('settings_languages'))
                                @if($site_global_settings->settingLanguage->$language == \App\SettingLanguage::LANGUAGE_ENABLE)
                                <a class="dropdown-item" href="{{ route('page.locale.update', ['user_prefer_language' => $setting_languages_key]) }}">
                                    {{ __('prefer_languages.' . $setting_languages_key) }}
                                </a>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-md-12">
                <div class="border-top pt-5">
                    <p>
                        {{ __('frontend.footer.copyright') }} &copy; {{ empty($site_global_settings->setting_site_name) ? config('app.name', 'Laravel') : $site_global_settings->setting_site_name }} {{ date('Y') }} {{ __('frontend.footer.rights-reserved') }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</footer>

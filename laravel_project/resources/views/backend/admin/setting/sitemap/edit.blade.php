@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('sitemap.edit-sitemap-setting') }}</h1>
            <p class="mb-4">{{ __('sitemap.edit-sitemap-setting-desc') }}</p>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.settings.sitemap.update') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-12">
                                <span class="text-gray-800 text-lg">{{ __('sitemap.sitemap-index') }}</span>
                                -
                                @if($all_sitemap_settings->setting_site_sitemap_index_enable == \App\Setting::SITE_SITEMAP_INDEX_ENABLE)
                                    <a href="{{ route('page.sitemap.index') }}" target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.index') }}
                                    </a>
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <i class="fas fa-external-link-alt"></i>
                                    <span>{{ route('page.sitemap.index') }}</span>
                                    <i class="fas fa-pause-circle text-warning"></i>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group pb-2">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_index_enable">{{ __('sitemap.sitemap-status') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_index_enable') is-invalid @enderror" name="setting_site_sitemap_index_enable">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_INDEX_ENABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_index_enable == \App\Setting::SITE_SITEMAP_INDEX_ENABLE ? 'selected' : '' }}>{{ __('sitemap.enable') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_INDEX_DISABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_index_enable == \App\Setting::SITE_SITEMAP_INDEX_DISABLE ? 'selected' : '' }}>{{ __('sitemap.disable') }}</option>
                                </select>
                                @error('setting_site_sitemap_index_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_show_in_footer">{{ __('sitemap.show-in-footer') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_show_in_footer') is-invalid @enderror" name="setting_site_sitemap_show_in_footer">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_SHOW_IN_FOOTER }}" {{ $all_sitemap_settings->setting_site_sitemap_show_in_footer == \App\Setting::SITE_SITEMAP_SHOW_IN_FOOTER ? 'selected' : '' }}>{{ __('sitemap.show') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_NOT_SHOW_IN_FOOTER }}" {{ $all_sitemap_settings->setting_site_sitemap_show_in_footer == \App\Setting::SITE_SITEMAP_NOT_SHOW_IN_FOOTER ? 'selected' : '' }}>{{ __('sitemap.not-show') }}</option>
                                </select>
                                @error('setting_site_sitemap_show_in_footer')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <div class="row form-group pb-2">
                            <div class="col-12">
                                <span>{{ __('sitemap.sitemap-include-to-index') }}:</span>
                                <ul>
                                    @if($all_sitemap_settings->setting_site_sitemap_page_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
                                        <li>
                                            <span>
                                            {{ __('sitemap.sitemap-page') . ' - ' . route('page.sitemap.page') }}
                                            </span>
                                        </li>
                                    @endif

                                    @if($all_sitemap_settings->setting_site_sitemap_category_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
                                        <li>
                                            <span>
                                            {{ __('sitemap.sitemap-category') . ' - ' . route('page.sitemap.category') }}
                                            </span>
                                        </li>
                                    @endif

                                    @if($all_sitemap_settings->setting_site_sitemap_listing_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
                                        <li>
                                            <span>
                                            {{ __('sitemap.sitemap-listing') . ' - ' . route('page.sitemap.listing') }}
                                            </span>
                                        </li>
                                    @endif

                                    @if($all_sitemap_settings->setting_site_sitemap_post_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
                                        <li>
                                            <span>
                                            {{ __('sitemap.sitemap-post') . ' - ' . route('page.sitemap.post') }}
                                            </span>
                                        </li>
                                    @endif

                                    @if($all_sitemap_settings->setting_site_sitemap_tag_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
                                        <li>
                                            <span>
                                            {{ __('sitemap.sitemap-tag') . ' - ' . route('page.sitemap.tag') }}
                                            </span>
                                        </li>
                                    @endif

                                    @if($all_sitemap_settings->setting_site_sitemap_topic_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
                                        <li>
                                            <span>
                                            {{ __('sitemap.sitemap-topic') . ' - ' . route('page.sitemap.topic') }}
                                            </span>
                                        </li>
                                    @endif

                                    @if($all_sitemap_settings->setting_site_sitemap_state_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
                                        <li>
                                        <span>
                                        {{ __('sitemap_import.sitemap.state') . ' - ' . route('page.sitemap.state') }}
                                        </span>
                                        </li>
                                    @endif

                                    @if($all_sitemap_settings->setting_site_sitemap_city_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX)
                                        <li>
                                        <span>
                                        {{ __('sitemap_import.sitemap.city') . ' - ' . route('page.sitemap.city') }}
                                        </span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <hr>
                        <div class="row form-group pt-2">
                            <div class="col-12">
                                <span class="text-gray-800 text-lg">{{ __('sitemap.sitemap-page') }}</span>
                                -
                                @if($all_sitemap_settings->setting_site_sitemap_page_enable == \App\Setting::SITE_SITEMAP_PAGE_ENABLE)
                                    <a href="{{ route('page.sitemap.page') }}" target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.page') }}
                                    </a>
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <span>
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.page') }}
                                    </span>
                                    <i class="fas fa-pause-circle text-warning"></i>
                                @endif
                            </div>
                        </div>
                        <div class="row form-group pb-2">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_page_enable">{{ __('sitemap.sitemap-status') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_page_enable') is-invalid @enderror" name="setting_site_sitemap_page_enable">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_PAGE_ENABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_page_enable == \App\Setting::SITE_SITEMAP_PAGE_ENABLE ? 'selected' : '' }}>{{ __('sitemap.enable') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_PAGE_DISABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_page_enable == \App\Setting::SITE_SITEMAP_PAGE_DISABLE ? 'selected' : '' }}>{{ __('sitemap.disable') }}</option>
                                </select>
                                @error('setting_site_sitemap_page_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_page_frequency">{{ __('sitemap.sitemap-frequency') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_page_frequency') is-invalid @enderror" name="setting_site_sitemap_page_frequency">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS }}" {{ $all_sitemap_settings->setting_site_sitemap_page_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-always') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY }}" {{ $all_sitemap_settings->setting_site_sitemap_page_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-hourly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY }}" {{ $all_sitemap_settings->setting_site_sitemap_page_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-daily') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY }}" {{ $all_sitemap_settings->setting_site_sitemap_page_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-weekly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY }}" {{ $all_sitemap_settings->setting_site_sitemap_page_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-monthly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY }}" {{ $all_sitemap_settings->setting_site_sitemap_page_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-yearly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER }}" {{ $all_sitemap_settings->setting_site_sitemap_page_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-never') }}</option>
                                </select>
                                @error('setting_site_sitemap_page_frequency')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_page_format">{{ __('sitemap.sitemap-format') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_page_format') is-invalid @enderror" name="setting_site_sitemap_page_format">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_XML }}" {{ $all_sitemap_settings->setting_site_sitemap_page_format == \App\Setting::SITE_SITEMAP_FORMAT_XML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-xml') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_HTML }}" {{ $all_sitemap_settings->setting_site_sitemap_page_format == \App\Setting::SITE_SITEMAP_FORMAT_HTML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-html') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_TXT }}" {{ $all_sitemap_settings->setting_site_sitemap_page_format == \App\Setting::SITE_SITEMAP_FORMAT_TXT ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-txt') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_page_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rss') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_page_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RDF ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rdf') }}</option>
                                </select>
                                @error('setting_site_sitemap_page_format')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_page_include_to_index">{{ __('sitemap.sitemap-include-to-index') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_page_include_to_index') is-invalid @enderror" name="setting_site_sitemap_page_include_to_index">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_page_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-include') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_page_include_to_index == \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-not-include') }}</option>
                                </select>
                                @error('setting_site_sitemap_page_include_to_index')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <hr>
                        <div class="row form-group pt-2">
                            <div class="col-12">
                                <span class="text-gray-800 text-lg">{{ __('sitemap.sitemap-category') }}</span>
                                -
                                @if($all_sitemap_settings->setting_site_sitemap_category_enable == \App\Setting::SITE_SITEMAP_CATEGORY_ENABLE)
                                    <a href="{{ route('page.sitemap.category') }}" target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.category') }}
                                    </a>
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <span>
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.category') }}
                                    </span>
                                    <i class="fas fa-pause-circle text-warning"></i>
                                @endif
                            </div>
                        </div>
                        <div class="row form-group pb-2">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_category_enable">{{ __('sitemap.sitemap-status') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_category_enable') is-invalid @enderror" name="setting_site_sitemap_category_enable">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_CATEGORY_ENABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_category_enable == \App\Setting::SITE_SITEMAP_CATEGORY_ENABLE ? 'selected' : '' }}>{{ __('sitemap.enable') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_CATEGORY_DISABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_category_enable == \App\Setting::SITE_SITEMAP_CATEGORY_DISABLE ? 'selected' : '' }}>{{ __('sitemap.disable') }}</option>
                                </select>
                                @error('setting_site_sitemap_category_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_category_frequency">{{ __('sitemap.sitemap-frequency') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_category_frequency') is-invalid @enderror" name="setting_site_sitemap_category_frequency">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS }}" {{ $all_sitemap_settings->setting_site_sitemap_category_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-always') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY }}" {{ $all_sitemap_settings->setting_site_sitemap_category_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-hourly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY }}" {{ $all_sitemap_settings->setting_site_sitemap_category_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-daily') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY }}" {{ $all_sitemap_settings->setting_site_sitemap_category_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-weekly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY }}" {{ $all_sitemap_settings->setting_site_sitemap_category_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-monthly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY }}" {{ $all_sitemap_settings->setting_site_sitemap_category_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-yearly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER }}" {{ $all_sitemap_settings->setting_site_sitemap_category_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-never') }}</option>
                                </select>
                                @error('setting_site_sitemap_category_frequency')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_category_format">{{ __('sitemap.sitemap-format') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_category_format') is-invalid @enderror" name="setting_site_sitemap_category_format">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_XML }}" {{ $all_sitemap_settings->setting_site_sitemap_category_format == \App\Setting::SITE_SITEMAP_FORMAT_XML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-xml') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_HTML }}" {{ $all_sitemap_settings->setting_site_sitemap_category_format == \App\Setting::SITE_SITEMAP_FORMAT_HTML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-html') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_TXT }}" {{ $all_sitemap_settings->setting_site_sitemap_category_format == \App\Setting::SITE_SITEMAP_FORMAT_TXT ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-txt') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_category_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rss') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_category_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RDF ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rdf') }}</option>
                                </select>
                                @error('setting_site_sitemap_category_format')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_category_include_to_index">{{ __('sitemap.sitemap-include-to-index') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_category_include_to_index') is-invalid @enderror" name="setting_site_sitemap_category_include_to_index">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_category_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-include') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_category_include_to_index == \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-not-include') }}</option>
                                </select>
                                @error('setting_site_sitemap_category_include_to_index')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <hr>
                        <div class="row form-group pt-2">
                            <div class="col-12">
                                <span class="text-gray-800 text-lg">{{ __('sitemap.sitemap-listing') }}</span>
                                -
                                @if($all_sitemap_settings->setting_site_sitemap_listing_enable == \App\Setting::SITE_SITEMAP_LISTING_ENABLE)
                                    <a href="{{ route('page.sitemap.listing') }}" target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.listing') }}
                                    </a>
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <span>
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.listing') }}
                                    </span>
                                    <i class="fas fa-pause-circle text-warning"></i>
                                @endif
                            </div>
                        </div>
                        <div class="row form-group pb-2">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_listing_enable">{{ __('sitemap.sitemap-status') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_listing_enable') is-invalid @enderror" name="setting_site_sitemap_listing_enable">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_LISTING_ENABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_enable == \App\Setting::SITE_SITEMAP_LISTING_ENABLE ? 'selected' : '' }}>{{ __('sitemap.enable') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_LISTING_DISABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_enable == \App\Setting::SITE_SITEMAP_LISTING_DISABLE ? 'selected' : '' }}>{{ __('sitemap.disable') }}</option>
                                </select>
                                @error('setting_site_sitemap_listing_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_listing_frequency">{{ __('sitemap.sitemap-frequency') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_listing_frequency') is-invalid @enderror" name="setting_site_sitemap_listing_frequency">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-always') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-hourly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-daily') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-weekly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-monthly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-yearly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-never') }}</option>
                                </select>
                                @error('setting_site_sitemap_listing_frequency')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_listing_format">{{ __('sitemap.sitemap-format') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_listing_format') is-invalid @enderror" name="setting_site_sitemap_listing_format">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_XML }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_format == \App\Setting::SITE_SITEMAP_FORMAT_XML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-xml') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_HTML }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_format == \App\Setting::SITE_SITEMAP_FORMAT_HTML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-html') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_TXT }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_format == \App\Setting::SITE_SITEMAP_FORMAT_TXT ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-txt') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rss') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RDF ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rdf') }}</option>
                                </select>
                                @error('setting_site_sitemap_listing_format')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_listing_include_to_index">{{ __('sitemap.sitemap-include-to-index') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_listing_include_to_index') is-invalid @enderror" name="setting_site_sitemap_listing_include_to_index">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-include') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_listing_include_to_index == \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-not-include') }}</option>
                                </select>
                                @error('setting_site_sitemap_listing_include_to_index')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <hr>
                        <div class="row form-group pt-2">
                            <div class="col-12">
                                <span class="text-gray-800 text-lg">{{ __('sitemap.sitemap-post') }}</span>
                                -
                                @if($all_sitemap_settings->setting_site_sitemap_post_enable == \App\Setting::SITE_SITEMAP_POST_ENABLE)
                                    <a href="{{ route('page.sitemap.post') }}" target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.post') }}
                                    </a>
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <span>
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.post') }}
                                    </span>
                                    <i class="fas fa-pause-circle text-warning"></i>
                                @endif
                            </div>
                        </div>
                        <div class="row form-group pb-2">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_post_enable">{{ __('sitemap.sitemap-status') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_post_enable') is-invalid @enderror" name="setting_site_sitemap_post_enable">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_POST_ENABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_post_enable == \App\Setting::SITE_SITEMAP_POST_ENABLE ? 'selected' : '' }}>{{ __('sitemap.enable') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_POST_DISABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_post_enable == \App\Setting::SITE_SITEMAP_POST_DISABLE ? 'selected' : '' }}>{{ __('sitemap.disable') }}</option>
                                </select>
                                @error('setting_site_sitemap_post_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_post_frequency">{{ __('sitemap.sitemap-frequency') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_post_frequency') is-invalid @enderror" name="setting_site_sitemap_post_frequency">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS }}" {{ $all_sitemap_settings->setting_site_sitemap_post_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-always') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY }}" {{ $all_sitemap_settings->setting_site_sitemap_post_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-hourly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY }}" {{ $all_sitemap_settings->setting_site_sitemap_post_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-daily') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY }}" {{ $all_sitemap_settings->setting_site_sitemap_post_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-weekly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY }}" {{ $all_sitemap_settings->setting_site_sitemap_post_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-monthly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY }}" {{ $all_sitemap_settings->setting_site_sitemap_post_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-yearly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER }}" {{ $all_sitemap_settings->setting_site_sitemap_post_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-never') }}</option>
                                </select>
                                @error('setting_site_sitemap_post_frequency')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_post_format">{{ __('sitemap.sitemap-format') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_post_format') is-invalid @enderror" name="setting_site_sitemap_post_format">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_XML }}" {{ $all_sitemap_settings->setting_site_sitemap_post_format == \App\Setting::SITE_SITEMAP_FORMAT_XML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-xml') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_HTML }}" {{ $all_sitemap_settings->setting_site_sitemap_post_format == \App\Setting::SITE_SITEMAP_FORMAT_HTML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-html') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_TXT }}" {{ $all_sitemap_settings->setting_site_sitemap_post_format == \App\Setting::SITE_SITEMAP_FORMAT_TXT ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-txt') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_post_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rss') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_post_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RDF ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rdf') }}</option>
                                </select>
                                @error('setting_site_sitemap_post_format')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_post_include_to_index">{{ __('sitemap.sitemap-include-to-index') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_post_include_to_index') is-invalid @enderror" name="setting_site_sitemap_post_include_to_index">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_post_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-include') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_post_include_to_index == \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-not-include') }}</option>
                                </select>
                                @error('setting_site_sitemap_post_include_to_index')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <hr>
                        <div class="row form-group pt-2">
                            <div class="col-12">
                                <span class="text-gray-800 text-lg">{{ __('sitemap.sitemap-tag') }}</span>
                                -
                                @if($all_sitemap_settings->setting_site_sitemap_tag_enable == \App\Setting::SITE_SITEMAP_TAG_ENABLE)
                                    <a href="{{ route('page.sitemap.tag') }}" target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.tag') }}
                                    </a>
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <span>
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.tag') }}
                                    </span>
                                    <i class="fas fa-pause-circle text-warning"></i>
                                @endif
                            </div>
                        </div>
                        <div class="row form-group pb-2">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_tag_enable">{{ __('sitemap.sitemap-status') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_tag_enable') is-invalid @enderror" name="setting_site_sitemap_tag_enable">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_TAG_ENABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_enable == \App\Setting::SITE_SITEMAP_TAG_ENABLE ? 'selected' : '' }}>{{ __('sitemap.enable') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_TAG_DISABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_enable == \App\Setting::SITE_SITEMAP_TAG_DISABLE ? 'selected' : '' }}>{{ __('sitemap.disable') }}</option>
                                </select>
                                @error('setting_site_sitemap_tag_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_tag_frequency">{{ __('sitemap.sitemap-frequency') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_tag_frequency') is-invalid @enderror" name="setting_site_sitemap_tag_frequency">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-always') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-hourly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-daily') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-weekly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-monthly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-yearly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-never') }}</option>
                                </select>
                                @error('setting_site_sitemap_tag_frequency')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_tag_format">{{ __('sitemap.sitemap-format') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_tag_format') is-invalid @enderror" name="setting_site_sitemap_tag_format">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_XML }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_format == \App\Setting::SITE_SITEMAP_FORMAT_XML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-xml') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_HTML }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_format == \App\Setting::SITE_SITEMAP_FORMAT_HTML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-html') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_TXT }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_format == \App\Setting::SITE_SITEMAP_FORMAT_TXT ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-txt') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rss') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RDF ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rdf') }}</option>
                                </select>
                                @error('setting_site_sitemap_tag_format')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_tag_include_to_index">{{ __('sitemap.sitemap-include-to-index') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_tag_include_to_index') is-invalid @enderror" name="setting_site_sitemap_tag_include_to_index">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-include') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_tag_include_to_index == \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-not-include') }}</option>
                                </select>
                                @error('setting_site_sitemap_tag_include_to_index')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <hr>
                        <div class="row form-group pt-2">
                            <div class="col-12">
                                <span class="text-gray-800 text-lg">{{ __('sitemap.sitemap-topic') }}</span>
                                -
                                @if($all_sitemap_settings->setting_site_sitemap_topic_enable == \App\Setting::SITE_SITEMAP_TOPIC_ENABLE)
                                    <a href="{{ route('page.sitemap.topic') }}" target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.topic') }}
                                    </a>
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <span>
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.topic') }}
                                    </span>
                                    <i class="fas fa-pause-circle text-warning"></i>
                                @endif
                            </div>
                        </div>
                        <div class="row form-group pb-2">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_topic_enable">{{ __('sitemap.sitemap-status') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_topic_enable') is-invalid @enderror" name="setting_site_sitemap_topic_enable">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_TOPIC_ENABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_enable == \App\Setting::SITE_SITEMAP_TOPIC_ENABLE ? 'selected' : '' }}>{{ __('sitemap.enable') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_TOPIC_DISABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_enable == \App\Setting::SITE_SITEMAP_TOPIC_DISABLE ? 'selected' : '' }}>{{ __('sitemap.disable') }}</option>
                                </select>
                                @error('setting_site_sitemap_topic_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_topic_frequency">{{ __('sitemap.sitemap-frequency') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_topic_frequency') is-invalid @enderror" name="setting_site_sitemap_topic_frequency">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-always') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-hourly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-daily') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-weekly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-monthly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-yearly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-never') }}</option>
                                </select>
                                @error('setting_site_sitemap_topic_frequency')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_topic_format">{{ __('sitemap.sitemap-format') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_topic_format') is-invalid @enderror" name="setting_site_sitemap_topic_format">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_XML }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_format == \App\Setting::SITE_SITEMAP_FORMAT_XML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-xml') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_HTML }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_format == \App\Setting::SITE_SITEMAP_FORMAT_HTML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-html') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_TXT }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_format == \App\Setting::SITE_SITEMAP_FORMAT_TXT ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-txt') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rss') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RDF ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rdf') }}</option>
                                </select>
                                @error('setting_site_sitemap_topic_format')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_topic_include_to_index">{{ __('sitemap.sitemap-include-to-index') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_topic_include_to_index') is-invalid @enderror" name="setting_site_sitemap_topic_include_to_index">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-include') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_topic_include_to_index == \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-not-include') }}</option>
                                </select>
                                @error('setting_site_sitemap_topic_include_to_index')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <hr>
                        <div class="row form-group pt-2">
                            <div class="col-12">
                                <span class="text-gray-800 text-lg">{{ __('sitemap_import.sitemap.state') }}</span>
                                -
                                @if($all_sitemap_settings->setting_site_sitemap_state_enable == \App\Setting::SITE_SITEMAP_STATE_ENABLE)
                                    <a href="{{ route('page.sitemap.state') }}" target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.state') }}
                                    </a>
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <span>
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.state') }}
                                    </span>
                                    <i class="fas fa-pause-circle text-warning"></i>
                                @endif
                            </div>
                        </div>
                        <div class="row form-group pb-2">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_state_enable">{{ __('sitemap.sitemap-status') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_state_enable') is-invalid @enderror" name="setting_site_sitemap_state_enable">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_STATE_ENABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_state_enable == \App\Setting::SITE_SITEMAP_STATE_ENABLE ? 'selected' : '' }}>{{ __('sitemap.enable') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_STATE_DISABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_state_enable == \App\Setting::SITE_SITEMAP_STATE_DISABLE ? 'selected' : '' }}>{{ __('sitemap.disable') }}</option>
                                </select>
                                @error('setting_site_sitemap_state_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_state_frequency">{{ __('sitemap.sitemap-frequency') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_state_frequency') is-invalid @enderror" name="setting_site_sitemap_state_frequency">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS }}" {{ $all_sitemap_settings->setting_site_sitemap_state_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-always') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY }}" {{ $all_sitemap_settings->setting_site_sitemap_state_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-hourly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY }}" {{ $all_sitemap_settings->setting_site_sitemap_state_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-daily') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY }}" {{ $all_sitemap_settings->setting_site_sitemap_state_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-weekly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY }}" {{ $all_sitemap_settings->setting_site_sitemap_state_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-monthly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY }}" {{ $all_sitemap_settings->setting_site_sitemap_state_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-yearly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER }}" {{ $all_sitemap_settings->setting_site_sitemap_state_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-never') }}</option>
                                </select>
                                @error('setting_site_sitemap_state_frequency')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_state_format">{{ __('sitemap.sitemap-format') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_state_format') is-invalid @enderror" name="setting_site_sitemap_state_format">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_XML }}" {{ $all_sitemap_settings->setting_site_sitemap_state_format == \App\Setting::SITE_SITEMAP_FORMAT_XML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-xml') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_HTML }}" {{ $all_sitemap_settings->setting_site_sitemap_state_format == \App\Setting::SITE_SITEMAP_FORMAT_HTML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-html') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_TXT }}" {{ $all_sitemap_settings->setting_site_sitemap_state_format == \App\Setting::SITE_SITEMAP_FORMAT_TXT ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-txt') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_state_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rss') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_state_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RDF ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rdf') }}</option>
                                </select>
                                @error('setting_site_sitemap_state_format')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_state_include_to_index">{{ __('sitemap.sitemap-include-to-index') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_state_include_to_index') is-invalid @enderror" name="setting_site_sitemap_state_include_to_index">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_state_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-include') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_state_include_to_index == \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-not-include') }}</option>
                                </select>
                                @error('setting_site_sitemap_state_include_to_index')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <hr>
                        <div class="row form-group pt-2">
                            <div class="col-12">
                                <span class="text-gray-800 text-lg">{{ __('sitemap_import.sitemap.city') }}</span>
                                -
                                @if($all_sitemap_settings->setting_site_sitemap_city_enable == \App\Setting::SITE_SITEMAP_CITY_ENABLE)
                                    <a href="{{ route('page.sitemap.city') }}" target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.city') }}
                                    </a>
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <span>
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ route('page.sitemap.city') }}
                                    </span>
                                    <i class="fas fa-pause-circle text-warning"></i>
                                @endif
                            </div>
                        </div>
                        <div class="row form-group pb-2">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_city_enable">{{ __('sitemap.sitemap-status') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_city_enable') is-invalid @enderror" name="setting_site_sitemap_city_enable">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_CITY_ENABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_city_enable == \App\Setting::SITE_SITEMAP_CITY_ENABLE ? 'selected' : '' }}>{{ __('sitemap.enable') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_CITY_DISABLE }}" {{ $all_sitemap_settings->setting_site_sitemap_city_enable == \App\Setting::SITE_SITEMAP_CITY_DISABLE ? 'selected' : '' }}>{{ __('sitemap.disable') }}</option>
                                </select>
                                @error('setting_site_sitemap_city_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_city_frequency">{{ __('sitemap.sitemap-frequency') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_city_frequency') is-invalid @enderror" name="setting_site_sitemap_city_frequency">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS }}" {{ $all_sitemap_settings->setting_site_sitemap_city_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_ALWAYS ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-always') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY }}" {{ $all_sitemap_settings->setting_site_sitemap_city_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_HOURLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-hourly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY }}" {{ $all_sitemap_settings->setting_site_sitemap_city_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_DAILY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-daily') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY }}" {{ $all_sitemap_settings->setting_site_sitemap_city_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_WEEKLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-weekly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY }}" {{ $all_sitemap_settings->setting_site_sitemap_city_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_MONTHLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-monthly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY }}" {{ $all_sitemap_settings->setting_site_sitemap_city_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_YEARLY ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-yearly') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER }}" {{ $all_sitemap_settings->setting_site_sitemap_city_frequency == \App\Setting::SITE_SITEMAP_FREQUENCY_NEVER ? 'selected' : '' }}>{{ __('sitemap.sitemap-frequency-never') }}</option>
                                </select>
                                @error('setting_site_sitemap_city_frequency')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_city_format">{{ __('sitemap.sitemap-format') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_city_format') is-invalid @enderror" name="setting_site_sitemap_city_format">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_XML }}" {{ $all_sitemap_settings->setting_site_sitemap_city_format == \App\Setting::SITE_SITEMAP_FORMAT_XML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-xml') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_HTML }}" {{ $all_sitemap_settings->setting_site_sitemap_city_format == \App\Setting::SITE_SITEMAP_FORMAT_HTML ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-html') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_TXT }}" {{ $all_sitemap_settings->setting_site_sitemap_city_format == \App\Setting::SITE_SITEMAP_FORMAT_TXT ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-txt') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_city_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rss') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_FORMAT_ROR_RSS }}" {{ $all_sitemap_settings->setting_site_sitemap_city_format == \App\Setting::SITE_SITEMAP_FORMAT_ROR_RDF ? 'selected' : '' }}>{{ __('sitemap.sitemap-format-ror-rdf') }}</option>
                                </select>
                                @error('setting_site_sitemap_city_format')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_sitemap_city_include_to_index">{{ __('sitemap.sitemap-include-to-index') }}</label>
                                <select class="custom-select @error('setting_site_sitemap_city_include_to_index') is-invalid @enderror" name="setting_site_sitemap_city_include_to_index">
                                    <option value="{{ \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_city_include_to_index == \App\Setting::SITE_SITEMAP_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-include') }}</option>
                                    <option value="{{ \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX }}" {{ $all_sitemap_settings->setting_site_sitemap_city_include_to_index == \App\Setting::SITE_SITEMAP_NOT_INCLUDE_TO_INDEX ? 'selected' : '' }}>{{ __('sitemap.sitemap-not-include') }}</option>
                                </select>
                                @error('setting_site_sitemap_city_include_to_index')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <hr>

                        <div class="row form-group justify-content-between">
                            <div class="col-8">
                                <button type="submit" class="btn btn-success text-white">
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

@extends('backend.admin.layouts.app')

@section('styles')
    <link href="{{ asset('backend/vendor/spectrum/spectrum.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('theme_directory_hub.theme-edit-color') }}</h1>
            <p class="mb-4">{{ __('theme_directory_hub.theme-edit-color-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.themes.index') }}" class="btn btn-info btn-icon-split">
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
                <div class="col-12 col-sm-4 col-md-3 mb-2">

                    @if($theme->theme_system_default == \App\Theme::THEME_SYSTEM_DEFAULT_YES)
                        <img src="{{ asset('frontend/images/placeholder/' . $theme->theme_preview_image) }}" alt="Image" class="img-fluid border">
                    @else
                        @if(empty($theme->theme_preview_image))
                            <img src="{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}" alt="Image" class="img-fluid border">
                        @else
                            <img src="{{ asset(\App\Theme::THEME_ASSETS . '/' . \App\Theme::THEME_ASSETS_FRONTEND . '/' . $theme->theme_identifier . '/placeholder/' . $theme->theme_preview_image) }}" alt="Image" class="img-fluid border">
                        @endif
                    @endif

                </div>
                <div class="col-12 col-sm-8 col-md-9">

                    @if($theme->theme_status == \App\Theme::THEME_STATUS_ACTIVE)
                        <div class="row mb-2">
                            <div class="col-12">
                                <span class="bg-success pl-2 pr-2 text-white">{{ __('theme_directory_hub.theme-current') }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <span class="text-gray-800">{{ $theme->theme_name }}</span>

                            @if($theme->theme_system_default == \App\Theme::THEME_SYSTEM_DEFAULT_YES)
                                <span class="bg-dark text-white ml-1 pl-2 pr-2">{{ __('theme_directory_hub.theme-system-default') }}</span>
                            @endif
                        </div>
                    </div>

                    @if(!empty($theme->theme_author))
                        <div class="row">
                            <div class="col-12">
                                <span>{{ __('theme_directory_hub.theme-by-author') . ' ' . $theme->theme_author }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-2">
                        <div class="col-12">
                            <p>{{ $theme->theme_description }}</p>
                        </div>
                    </div>

                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-12 col-md-10 col-lg-6">
                    <form method="POST" action="{{ route('admin.themes.customization.color.update', ['theme' => $theme]) }}">
                        @csrf

                        <div class="row mb-2">
                            <div class="col-12">
                                <span>{{ __('customization.site-primary-color') }}</span>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12">
                                <input id="site_primary_color" class="color-picker-input" name="site_primary_color" value="{{ old('site_primary_color') ? old('site_primary_color') : $site_primary_color }}">
                                @error('site_primary_color')
                                <span class="invalid-tooltip">
                            <strong>{{ $message }}</strong>
                        </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12">
                                <span>{{ __('customization.site-header-background-color') }}</span>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <input id="site_header_background_color" class="color-picker-input" name="site_header_background_color" value="{{ old('site_header_background_color') ? old('site_header_background_color') : $site_header_background_color }}">
                                @error('site_header_background_color')
                                <span class="invalid-tooltip">
                            <strong>{{ $message }}</strong>
                        </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-2">
                            <div class="col-12">
                                <span>{{ __('customization.site-header-font-color') }}</span>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <input id="site_header_font_color" class="color-picker-input" name="site_header_font_color" value="{{ old('site_header_font_color') ? old('site_header_font_color') : $site_header_font_color }}">
                                @error('site_header_font_color')
                                <span class="invalid-tooltip">
                            <strong>{{ $message }}</strong>
                        </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-2">
                            <div class="col-12">
                                <span>{{ __('customization.site-footer-background-color') }}</span>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <input id="site_footer_background_color" class="color-picker-input" name="site_footer_background_color" value="{{ old('site_footer_background_color') ? old('site_footer_background_color') : $site_footer_background_color }}">
                                @error('site_footer_background_color')
                                <span class="invalid-tooltip">
                            <strong>{{ $message }}</strong>
                        </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-2">
                            <div class="col-12">
                                <span>{{ __('customization.site-footer-font-color') }}</span>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <input id="site_footer_font_color" class="color-picker-input" name="site_footer_font_color" value="{{ old('site_footer_font_color') ? old('site_footer_font_color') : $site_footer_font_color }}">
                                @error('site_footer_font_color')
                                <span class="invalid-tooltip">
                            <strong>{{ $message }}</strong>
                        </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-6">
                                <button type="submit" class="btn btn-success text-white">
                                    {{ __('backend.shared.update') }}
                                </button>
                            </div>
                            <div class="col-6 text-right">
                                <a class="text-info" href="#" data-toggle="modal" data-target="#restoreModal">
                                    {{ __('customization.restore') }}
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Restore Default Modal -->
    <div class="modal fade" id="restoreModal" tabindex="-1" role="dialog" aria-labelledby="restoreModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('customization.restore') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('theme_directory_hub.alert.theme-color-restore-confirm') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.themes.customization.color.restore', ['theme' => $theme]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info">{{ __('customization.restore-confirm-button') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('backend/vendor/spectrum/spectrum.min.js') }}"></script>
    <script>
        $(document).ready(function(){

            "use strict";

            $('#site_primary_color').spectrum({
                type: "component",
                togglePaletteOnly: "true",
                showInput: "true",
                showInitial: "true",
                showAlpha: "false"
            });

            $('#site_header_background_color').spectrum({
                type: "component",
                togglePaletteOnly: "true",
                showInput: "true",
                showInitial: "true",
                showAlpha: "false"
            });

            $('#site_header_font_color').spectrum({
                type: "component",
                togglePaletteOnly: "true",
                showInput: "true",
                showInitial: "true",
                showAlpha: "false"
            });

            $('#site_footer_background_color').spectrum({
                type: "component",
                togglePaletteOnly: "true",
                showInput: "true",
                showInitial: "true",
                showAlpha: "false"
            });

            $('#site_footer_font_color').spectrum({
                type: "component",
                togglePaletteOnly: "true",
                showInput: "true",
                showInitial: "true",
                showAlpha: "false"
            });
        });
    </script>
@endsection

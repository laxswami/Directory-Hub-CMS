@extends('backend.admin.layouts.app')

@section('styles')
    <link href="{{ asset('backend/vendor/spectrum/spectrum.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('customization.color') }}</h1>
            <p class="mb-4">{{ __('customization.color-desc') }}</p>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">


            <div class="row">
                <div class="col-12 col-md-10 col-lg-6">
                    <form method="POST" action="{{ route('admin.customization.color.update') }}">
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

                        <div class="row mb-2">
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
                    {{ __('customization.restore-confirm') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.customization.color.restore') }}" method="POST">
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

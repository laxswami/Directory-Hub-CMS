@extends('backend.admin.layouts.app')

@section('styles')
    <link href="{{ asset('backend/vendor/spectrum/spectrum.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('customization.header') }}</h1>
            <p class="mb-4">{{ __('customization.header-desc') }}</p>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row">
                <div class="col-12 col-md-10 col-lg-6">

                    <form method="POST" action="{{ route('admin.customization.header.update') }}" class="">
                        @csrf

                        <div class="row mb-2">
                            <div class="col-12">
                                <span>{{ __('customization.homepage-header-title-color') }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input id="site_homepage_header_title_font_color" class="color-picker-input" name="site_homepage_header_title_font_color" value="{{ old('site_homepage_header_title_font_color') ? old('site_homepage_header_title_font_color') : $site_homepage_header_title_font_color }}">
                                @error('site_homepage_header_title_font_color')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12">
                                <span>{{ __('customization.homepage-header-paragraph-color') }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input id="site_homepage_header_paragraph_font_color" class="color-picker-input" name="site_homepage_header_paragraph_font_color" value="{{ old('site_homepage_header_paragraph_font_color') ? old('site_homepage_header_paragraph_font_color') : $site_homepage_header_paragraph_font_color }}">
                                @error('site_homepage_header_paragraph_font_color')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="site_homepage_header_background_type">{{ __('customization.homepage-header-background-type') }}</label>
                                <select class="custom-select @error('site_homepage_header_background_type') is-invalid @enderror" name="site_homepage_header_background_type">

                                    <option {{ (old('site_homepage_header_background_type') ? old('site_homepage_header_background_type') : $site_homepage_header_background_type) == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_DEFAULT ? 'selected' : '' }} value="{{ \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_DEFAULT }}">
                                        {{ __('customization.homepage-header-background-type-default') }}
                                    </option>

                                    <option {{ (old('site_homepage_header_background_type') ? old('site_homepage_header_background_type') : $site_homepage_header_background_type) == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_COLOR ? 'selected' : '' }} value="{{ \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_COLOR }}">
                                        {{ __('customization.homepage-header-background-type-color') }}
                                    </option>

                                    <option {{ (old('site_homepage_header_background_type') ? old('site_homepage_header_background_type') : $site_homepage_header_background_type) == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_IMAGE ? 'selected' : '' }} value="{{ \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_IMAGE }}">
                                        {{ __('customization.homepage-header-background-type-image') }}
                                    </option>

                                    <option {{ (old('site_homepage_header_background_type') ? old('site_homepage_header_background_type') : $site_homepage_header_background_type) == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO ? 'selected' : '' }} value="{{ \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO }}">
                                        {{ __('customization.homepage-header-background-type-video') }}
                                    </option>

                                </select>
                                @error('site_homepage_header_background_type')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>

                        <div class="row mb-2">
                            <div class="col-12">
                                <span>{{ __('customization.homepage-header-background-color') }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input id="site_homepage_header_background_color" class="color-picker-input" name="site_homepage_header_background_color" value="{{ old('site_homepage_header_background_color') ? old('site_homepage_header_background_color') : $site_homepage_header_background_color }}">
                                @error('site_homepage_header_background_color')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="site_homepage_header_background_image" class="text-black">{{ __('customization.homepage-header-background-image') }}</label>
                                <small class="form-text text-muted">
                                    {{ __('customization.homepage-header-background-image-help') }}
                                </small>
                                <div class="input-group mb-2">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            {{ __('customization.browse') }} <input type="file" id="site_homepage_header_background_image_selector">
                                        </span>
                                    </span>
                                    <input type="text" class="form-control" readonly>
                                    <input type="hidden" name="site_homepage_header_background_image" id="site_homepage_header_background_image">
                                </div>
                                @if(empty($site_homepage_header_background_image))
                                    <img id='img-upload-homepage' class="img-upload-preview">
                                @else
                                    <img id='img-upload-homepage' class="img-upload-preview" src="{{ Storage::disk('public')->url('customization/'. $site_homepage_header_background_image) }}">
                                @endif

                                @error('site_homepage_header_background_image')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group pb-4">
                            <div class="col-md-12">
                                <label for="site_homepage_header_background_youtube_video" class="text-black">{{ __('customization.homepage-header-background-video') }}</label>
                                <input id="site_homepage_header_background_youtube_video" type="text" class="form-control @error('site_homepage_header_background_youtube_video') is-invalid @enderror" name="site_homepage_header_background_youtube_video" value="{{ old('site_homepage_header_background_youtube_video') ? old('site_homepage_header_background_youtube_video') : $site_homepage_header_background_youtube_video }}">
                                <small class="form-text text-muted">
                                    {{ __('customization.homepage-header-background-video-help') }}
                                </small>
                                @error('site_homepage_header_background_youtube_video')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <hr>


                        <div class="row mb-2">
                            <div class="col-12">
                                <span>{{ __('customization.innerpage-header-title-color') }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input id="site_innerpage_header_title_font_color" class="color-picker-input" name="site_innerpage_header_title_font_color" value="{{ old('site_innerpage_header_title_font_color') ? old('site_innerpage_header_title_font_color') : $site_innerpage_header_title_font_color }}">
                                @error('site_innerpage_header_title_font_color')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-2">
                            <div class="col-12">
                                <span>{{ __('customization.innerpage-header-paragraph-color') }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input id="site_innerpage_header_paragraph_font_color" class="color-picker-input" name="site_innerpage_header_paragraph_font_color" value="{{ old('site_innerpage_header_paragraph_font_color') ? old('site_innerpage_header_paragraph_font_color') : $site_innerpage_header_paragraph_font_color }}">
                                @error('site_innerpage_header_paragraph_font_color')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row form-group pt-4">
                            <div class="col-md-12">
                                <label class="text-black" for="site_innerpage_header_background_type">{{ __('customization.innerpage-header-background-type') }}</label>
                                <select class="custom-select @error('site_innerpage_header_background_type') is-invalid @enderror" name="site_innerpage_header_background_type">

                                    <option {{ (old('site_innerpage_header_background_type') ? old('site_innerpage_header_background_type') : $site_innerpage_header_background_type) == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_DEFAULT ? 'selected' : '' }} value="{{ \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_DEFAULT }}">
                                        {{ __('customization.innerpage-header-background-type-default') }}
                                    </option>

                                    <option {{ (old('site_innerpage_header_background_type') ? old('site_innerpage_header_background_type') : $site_innerpage_header_background_type) == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR ? 'selected' : '' }} value="{{ \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR }}">
                                        {{ __('customization.innerpage-header-background-type-color') }}
                                    </option>

                                    <option {{ (old('site_innerpage_header_background_type') ? old('site_innerpage_header_background_type') : $site_innerpage_header_background_type) == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE ? 'selected' : '' }} value="{{ \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE }}">
                                        {{ __('customization.innerpage-header-background-type-image') }}
                                    </option>

                                    <option {{ (old('site_innerpage_header_background_type') ? old('site_innerpage_header_background_type') : $site_innerpage_header_background_type) == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO ? 'selected' : '' }} value="{{ \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO }}">
                                        {{ __('customization.innerpage-header-background-type-video') }}
                                    </option>

                                </select>
                                @error('site_innerpage_header_background_type')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>


                        <div class="row mb-2">
                            <div class="col-12">
                                <span>{{ __('customization.innerpage-header-background-color') }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input id="site_innerpage_header_background_color" class="color-picker-input" name="site_innerpage_header_background_color" value="{{ old('site_innerpage_header_background_color') ? old('site_innerpage_header_background_color') : $site_innerpage_header_background_color }}">
                                @error('site_innerpage_header_background_color')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="site_innerpage_header_background_image" class="text-black">{{ __('customization.innerpage-header-background-image') }}</label>
                                <small class="form-text text-muted">
                                    {{ __('customization.innerpage-header-background-image-help') }}
                                </small>
                                <div class="input-group mb-2">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            {{ __('customization.browse') }} <input type="file" id="site_innerpage_header_background_image_selector">
                                        </span>
                                    </span>
                                    <input type="text" class="form-control" readonly>
                                    <input type="hidden" name="site_innerpage_header_background_image" id="site_innerpage_header_background_image">
                                </div>

                                @if(empty($site_innerpage_header_background_image))
                                    <img id='img-upload-innerpage' class="img-upload-preview">
                                @else
                                    <img id='img-upload-innerpage' class="img-upload-preview" src="{{ Storage::disk('public')->url('customization/'. $site_innerpage_header_background_image) }}">
                                @endif

                                @error('site_innerpage_header_background_image')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="site_innerpage_header_background_youtube_video" class="text-black">{{ __('customization.innerpage-header-background-video') }}</label>
                                <input id="site_innerpage_header_background_youtube_video" type="text" class="form-control @error('site_innerpage_header_background_youtube_video') is-invalid @enderror" name="site_innerpage_header_background_youtube_video" value="{{ old('site_innerpage_header_background_youtube_video') ? old('site_innerpage_header_background_youtube_video') : $site_innerpage_header_background_youtube_video }}">
                                <small class="form-text text-muted">
                                    {{ __('customization.innerpage-header-background-video-help') }}
                                </small>
                                @error('site_innerpage_header_background_youtube_video')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

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
    <script src="{{ asset('backend/vendor/spectrum/spectrum.min.js') }}"></script>
    <script>
        $(document).ready(function(){

            "use strict";

            /**
             * Start color picker
             */
            $('#site_homepage_header_background_color').spectrum({
                type: "component",
                togglePaletteOnly: "true",
                showInput: "true",
                showInitial: "true",
                showAlpha: "false"
            });

            $('#site_innerpage_header_background_color').spectrum({
                type: "component",
                togglePaletteOnly: "true",
                showInput: "true",
                showInitial: "true",
                showAlpha: "false"
            });

            $('#site_homepage_header_title_font_color').spectrum({
                type: "component",
                togglePaletteOnly: "true",
                showInput: "true",
                showInitial: "true",
                showAlpha: "false"
            });

            $('#site_homepage_header_paragraph_font_color').spectrum({
                type: "component",
                togglePaletteOnly: "true",
                showInput: "true",
                showInitial: "true",
                showAlpha: "false"
            });

            $('#site_innerpage_header_title_font_color').spectrum({
                type: "component",
                togglePaletteOnly: "true",
                showInput: "true",
                showInitial: "true",
                showAlpha: "false"
            });

            $('#site_innerpage_header_paragraph_font_color').spectrum({
                type: "component",
                togglePaletteOnly: "true",
                showInput: "true",
                showInitial: "true",
                showAlpha: "false"
            });

            /**
             * End color picker
             */

            /**
             * Start image file upload preview
             */
            $(document).on('change', '.btn-file :file', function() {
                var input = $(this),
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [label]);
            });

            $('.btn-file :file').on('fileselect', function(event, label) {

                var input = $(this).parents('.input-group').find(':text'),
                    log = label;

                if( input.length ) {
                    input.val(log);
                } else {
                    if( log ) alert(log);
                }

            });
            function readURL(input, preview_img_id, input_id) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#' + preview_img_id).attr('src', e.target.result);
                        $('#' + input_id).attr('value', e.target.result);

                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#site_homepage_header_background_image_selector").change(function(){
                readURL(this, "img-upload-homepage", "site_homepage_header_background_image");
            });

            $("#site_innerpage_header_background_image_selector").change(function(){
                readURL(this, "img-upload-innerpage", "site_innerpage_header_background_image");
            });
            /**
             * End image file upload preview
             */
        });
    </script>
@endsection

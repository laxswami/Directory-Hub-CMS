@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Image Crop Css -->
    <link href="{{ asset('backend/vendor/croppie/croppie.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/vendor/spectrum/spectrum.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.category.add-category') }}</h1>
            <p class="mb-4">{{ __('backend.category.add-category-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-info btn-icon-split">
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
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.categories.store') }}" class="">
                        @csrf

                        <div class="row border-left-primary mb-4">
                            <div class="col-12">

                                <div class="form-row mb-4 bg-primary pl-1 pt-1 pb-1">
                                    <div class="col-md-12">
                                        <span class="text-lg text-white">
                                             <i class="fa-solid fa-layer-group"></i>
                                            {{ __('category_image_option.section-general-information') }}
                                        </span>
                                        <small class="form-text text-white">
                                            {{ __('category_image_option.section-general-information-desc') }}
                                        </small>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-4">
                                        <label for="category_name" class="text-black">{{ __('backend.category.category-name') }}</label>
                                        <input id="category_name" type="text" class="form-control @error('category_name') is-invalid @enderror" name="category_name" value="{{ old('category_name') }}">
                                        @error('category_name')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="category_slug" class="text-black">{{ __('backend.category.slug') }}</label>
                                        <input id="category_slug" type="text" class="form-control @error('category_slug') is-invalid @enderror" name="category_slug" value="{{ old('category_slug') }}">
                                        <small class="text-muted">
                                            {{ __('categories.category-slug-help') }}
                                        </small>
                                        @error('category_slug')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="text-black" for="category_parent_id">{{ __('categories.choose-parent-cat') }}</label>
                                        <select class="custom-select @error('category_parent_id') is-invalid @enderror" name="category_parent_id">
                                            <option value="0">{{ __('categories.no-parent-cat') }}</option>
                                            @foreach($printable_categories as $key => $printable_category)
                                                <option value="{{ $printable_category["category_id"] }}" {{ old('category_parent_id') == $printable_category["category_id"] ? 'selected' : '' }}>{{ $printable_category["category_name"] }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_parent_id')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-12">
                                        <label class="text-black" for="category_description">{{ __('category_description.category-description') }}</label>
                                        <textarea id="category_description" class="form-control @error('category_description') is-invalid @enderror" name="category_description">{{ old('category_description') }}</textarea>
                                        @error('category_description')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row mb-4 bg-primary pl-1 pt-1 pb-1">
                                    <div class="col-md-12">
                                        <span class="text-lg text-white">
                                             <i class="fa-solid fa-image"></i>
                                            {{ __('category_image_option.section-icon-or-image') }}
                                        </span>
                                        <small class="form-text text-white">
                                            {{ __('category_image_option.section-icon-or-image-desc') }}
                                        </small>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-12 col-md-6">
                                        <div class="form-check">
                                            <input {{ old('category_thumbnail_type') == \App\Category::CATEGORY_THUMBNAIL_TYPE_ICON ? 'checked' : '' }} class="form-check-input" type="radio" name="category_thumbnail_type" id="category_thumbnail_type_icon" value="{{ \App\Category::CATEGORY_THUMBNAIL_TYPE_ICON }}" aria-describedby="category_thumbnail_type_icon_HelpBlock">
                                            <label class="form-check-label" for="category_thumbnail_type_icon">
                                                {{ __('category_image_option.form-category-icon-image-select-icon') }}
                                            </label>
                                            <small id="category_thumbnail_type_icon_HelpBlock" class="form-text text-muted">
                                                {{ __('category_image_option.form-category-icon-image-select-icon-help') }}
                                            </small>
                                            @error('category_thumbnail_type')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-check">
                                            <input {{ old('category_thumbnail_type') == \App\Category::CATEGORY_THUMBNAIL_TYPE_IMAGE ? 'checked' : '' }} class="form-check-input" type="radio" name="category_thumbnail_type" id="category_thumbnail_type_image" value="{{ \App\Category::CATEGORY_THUMBNAIL_TYPE_IMAGE }}" aria-describedby="category_thumbnail_type_image_HelpBlock">
                                            <label class="form-check-label" for="category_thumbnail_type_image">
                                                {{ __('category_image_option.form-category-icon-image-select-image') }}
                                            </label>
                                            <small id="category_thumbnail_type_image_HelpBlock" class="form-text text-muted">
                                                {{ __('category_image_option.form-category-icon-image-select-image-help') }}
                                            </small>
                                            @error('category_thumbnail_type')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-6">
                                        <label class="text-black" for="category_icon">{{ __('backend.category.category-icon') }}</label>
                                        <input id="category_icon" type="text" class="form-control @error('category_icon') is-invalid @enderror" name="category_icon" value="{{ old('category_icon') }}">
                                        <small class="text-muted">
                                            {!! __('backend.category.category-icon-help') !!}
                                        </small>
                                        @error('category_icon')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-black">{{ __('category_image_option.form-category-image') }}</label>
                                        <small class="form-text text-muted">
                                            {{ __('category_image_option.form-category-image-help') }}
                                        </small>
                                        @error('category_image')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <button id="upload_image" type="button" class="btn btn-primary btn-sm mb-2">
                                                    <i class="fa-solid fa-file-image"></i>
                                                    {{ __('backend.item.select-image') }}
                                                </button>
                                                <a class="btn btn-danger btn-sm text-white" id="delete_category_image_button">
                                                    <i class="fas fa-trash-alt"></i>
                                                    {{ __('category_image_option.form-category-image-delete') }}
                                                </a>
                                                <input id="category_image" type="hidden" name="category_image" value="{{ old('category_image') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <img id="image_preview" src="{{ old('category_image') ? old('category_image') : asset('backend/images/placeholder/full_item_feature_image.webp') }}" class="img-responsive">
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row mb-4 bg-primary pl-1 pt-1 pb-1">
                                    <div class="col-md-12">
                                        <span class="text-lg text-white">
                                             <i class="fa-solid fa-palette"></i>
                                            {{ __('category_image_option.section-header-background') }}
                                        </span>
                                        <small class="form-text text-white">
                                            {{ __('category_image_option.section-header-background-desc') }}
                                        </small>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-12">
                                        <label class="text-black" for="category_header_background_type">{{ __('category_image_option.form-header-background-type') }}</label>
                                        <select class="custom-select @error('category_header_background_type') is-invalid @enderror" name="category_header_background_type">
                                            <option value="{{ \App\Category::CATEGORY_HEADER_BACKGROUND_TYPE_INHERITED }}" {{ old('category_header_background_type') == \App\Category::CATEGORY_HEADER_BACKGROUND_TYPE_INHERITED ? 'selected' : '' }}>{{ __('category_image_option.form-header-background-inherited') }}</option>
                                            <option value="{{ \App\Category::CATEGORY_HEADER_BACKGROUND_TYPE_COLOR }}" {{ old('category_header_background_type') == \App\Category::CATEGORY_HEADER_BACKGROUND_TYPE_COLOR ? 'selected' : '' }}>{{ __('category_image_option.form-header-background-color') }}</option>
                                            <option value="{{ \App\Category::CATEGORY_HEADER_BACKGROUND_TYPE_IMAGE }}" {{ old('category_header_background_type') == \App\Category::CATEGORY_HEADER_BACKGROUND_TYPE_IMAGE ? 'selected' : '' }}>{{ __('category_image_option.form-header-background-image') }}</option>
                                            <option value="{{ \App\Category::CATEGORY_HEADER_BACKGROUND_TYPE_VIDEO }}" {{ old('category_header_background_type') == \App\Category::CATEGORY_HEADER_BACKGROUND_TYPE_VIDEO ? 'selected' : '' }}>{{ __('category_image_option.form-header-background-video') }}</option>
                                        </select>
                                        <small class="form-text text-muted">
                                            {{ __('category_image_option.form-header-background-type-help') }}
                                        </small>
                                        @error('category_header_background_type')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-4">
                                        <label class="text-black" for="category_header_background_color">{{ __('category_image_option.form-header-background-color') }}</label>
                                        <input id="category_header_background_color" class="color-picker-input" name="category_header_background_color" value="{{ old('category_header_background_color') }}">
                                        <small class="form-text text-muted">
                                            {{ __('category_image_option.form-header-background-color-help') }}
                                        </small>
                                        @error('category_header_background_color')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="category_header_background_image" class="text-black">{{ __('category_image_option.form-header-background-image') }}</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-btn">
                                                <span class="btn btn-primary btn-file">
                                                    {{ __('customization.browse') }} <input type="file" id="category_header_background_image_selector">
                                                </span>
                                            </span>
                                            <input type="text" class="form-control" readonly>
                                            <input type="hidden" name="category_header_background_image" id="category_header_background_image" value="{{ old('category_header_background_image') }}">
                                        </div>
                                        <small class="form-text text-muted">
                                            {{ __('category_image_option.form-header-background-image-help') }}
                                        </small>
                                        <img id='img-upload-homepage' class="img-upload-preview" src="{{ old('category_header_background_image') }}">
                                        @error('category_header_background_image')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="category_header_background_youtube_video" class="text-black">{{ __('category_image_option.form-header-background-video') }}</label>
                                        <input id="category_header_background_youtube_video" type="text" class="form-control @error('category_header_background_youtube_video') is-invalid @enderror" name="category_header_background_youtube_video" value="{{ old('category_header_background_youtube_video') }}">
                                        <small class="form-text text-muted">
                                            {{ __('category_image_option.form-header-background-video-help') }}
                                        </small>
                                        @error('category_header_background_youtube_video')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
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

<!-- Modal - category image -->
<div class="modal fade" id="image-crop-modal" tabindex="-1" role="dialog" aria-labelledby="image-crop-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('category_image_option.modal-category-image-crop-title') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div id="image_demo"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="custom-file">
                            <input id="upload_image_input" type="file" class="custom-file-input">
                            <label class="custom-file-label" for="upload_image_input">{{ __('backend.item.choose-image') }}</label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                <button id="crop_image" type="button" class="btn btn-primary">{{ __('backend.item.crop-image') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <!-- Image Crop Plugin Js -->
    <script src="{{ asset('backend/vendor/croppie/croppie.js') }}"></script>
    <script src="{{ asset('backend/vendor/spectrum/spectrum.min.js') }}"></script>

    <script>

        $(document).ready(function() {

            "use strict";

            /**
             * Start color picker
             */
            $('#category_header_background_color').spectrum({
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
             * Start the croppie image plugin
             */
            var image_crop = null;

            $('#upload_image').on('click', function(){

                $('#image-crop-modal').modal('show');
            });

            var window_height = $(window).height();
            var window_width = $(window).width();
            var viewport_height = 0;
            var viewport_width = 0;

            if(window_width >= 317)
            {
                viewport_width = 317;
                viewport_height = 200;
            }
            else
            {
                viewport_width = window_width * 0.8;
                viewport_height = (viewport_width * 200) / 317;
            }

            $('#upload_image_input').on('change', function(){

                if(!image_crop)
                {
                    image_crop = $('#image_demo').croppie({
                        enableExif: true,
                        mouseWheelZoom: false,
                        viewport: {
                            width:viewport_width,
                            height:viewport_height,
                            type:'square',
                        },
                        boundary:{
                            width:viewport_width + 5,
                            height:viewport_width + 5,
                        }
                    });

                    $('#image-crop-modal .modal-dialog').css({
                        'max-width':'100%'
                    });
                }

                var reader = new FileReader();

                reader.onload = function (event) {

                    image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function(){
                        console.log('jQuery bind complete');
                    });

                };
                reader.readAsDataURL(this.files[0]);
            });

            $('#crop_image').on("click", function(event){

                image_crop.croppie('result', {
                    type: 'base64',
                    size: 'viewport'
                }).then(function(response){
                    $('#category_image').val(response);
                    $('#image_preview').attr("src", response);
                });

                $('#image-crop-modal').modal('hide')
            });
            /**
             * End the croppie image plugin
             */

            /**
             * Start delete feature image button
             */
            $('#delete_category_image_button').on('click', function(){

                $('#delete_category_image_button').attr("disabled", true);

                $('#image_preview').attr("src", "{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}");
                $('#category_image').val("");

                $('#delete_category_image_button').attr("disabled", false);
            });
            /**
             * End delete feature image button
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

            $("#category_header_background_image_selector").change(function(){
                readURL(this, "img-upload-homepage", "category_header_background_image");
            });
            /**
             * End image file upload preview
             */
        });
    </script>
@endsection

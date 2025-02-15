@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Image Crop Css -->
    <link href="{{ asset('backend/vendor/croppie/croppie.css') }}" rel="stylesheet" />

@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.testimonial.add-testimonial') }}</h1>
            <p class="mb-4">{{ __('backend.testimonial.add-testimonial-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-info btn-icon-split">
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
                <div class="col-12 col-md-10 col-lg-6">
                    <form method="POST" action="{{ route('admin.testimonials.store') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="testimonial_name" class="text-black">{{ __('backend.testimonial.name') }}</label>
                                <input id="testimonial_name" type="text" class="form-control @error('testimonial_name') is-invalid @enderror" name="testimonial_name" value="{{ old('testimonial_name') }}" autofocus>
                                @error('testimonial_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="testimonial_company">{{ __('backend.testimonial.company') }}</label>
                                <input id="testimonial_company" type="text" class="form-control @error('testimonial_company') is-invalid @enderror" name="testimonial_company" value="{{ old('testimonial_company') }}">
                                @error('testimonial_company')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="testimonial_job_title">{{ __('backend.testimonial.job-title') }}</label>
                                <input id="testimonial_job_title" type="text" class="form-control @error('testimonial_job_title') is-invalid @enderror" name="testimonial_job_title" value="{{ old('testimonial_job_title') }}">
                                @error('testimonial_job_title')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="testimonial_description">{{ __('backend.testimonial.description') }}</label>

                                <textarea rows="4" id="testimonial_description" type="text" class="form-control @error('testimonial_description') is-invalid @enderror" name="testimonial_description">{{ old('testimonial_description') }}</textarea>
                                @error('testimonial_description')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-6">
                                <span class="text-lg text-gray-800">{{ __('backend.testimonial.profile-image') }}</span>
                                <small class="form-text text-muted">
                                    {{ __('backend.testimonial.profile-image-help') }}
                                </small>
                                @error('testimonial_image')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="row mt-3">
                                    <div class="col-8">
                                        <button id="upload_image" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.testimonial.select-image') }}</button>
                                        <img id="image_preview" src="{{ asset('backend/images/placeholder/profile-' . intval(rand(0,9)) . '.webp') }}" class="img-responsive">
                                        <input id="feature_image" type="hidden" name="testimonial_image">
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

    <!-- Modal -->
    <div class="modal fade" id="image-crop-modal" tabindex="-1" role="dialog" aria-labelledby="image-crop-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.testimonial.crop-profile-image') }}</h5>
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
                                <label class="custom-file-label" for="upload_image_input">{{ __('backend.testimonial.choose-image') }}</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button id="crop_image" type="button" class="btn btn-primary">{{ __('backend.testimonial.crop-image') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Image Crop Plugin Js -->
    <script src="{{ asset('backend/vendor/croppie/croppie.js') }}"></script>

    <script>
        $(document).ready(function() {

            "use strict";

            /**
             * Start the croppie image plugin
             */
            var image_crop = null;

            $('#upload_image').on('click', function(){

                $('#image-crop-modal').modal('show');
            });


            $('#upload_image_input').on('change', function(){

                if(!image_crop)
                {
                    image_crop = $('#image_demo').croppie({
                        enableExif: true,
                        mouseWheelZoom: false,
                        viewport: {
                            width:100,
                            height:100,
                            type:'square'
                        },
                        boundary:{
                            width:300,
                            height:300
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
                    $('#feature_image').val(response);
                    $('#image_preview').attr("src", response);
                });

                $('#image-crop-modal').modal('hide');
            });
            /**
             * End the croppie image plugin
             */

        });
    </script>
@endsection

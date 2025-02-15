@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Image Crop Css -->
    <link href="{{ asset('backend/vendor/croppie/croppie.css') }}" rel="stylesheet" />

    <!-- Bootstrap FD Css-->
    <link href="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('products.edit') }}</h1>
            <p class="mb-4">{{ __('products.edit-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.products.index') }}" class="btn btn-info btn-icon-split">
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

            <div class="row mb-3">
                <div class="col-12">
                    @if($product_owner->isUser())
                        <div class="alert alert-warning" role="alert">
                            {{ __('products.edit-owner-alert', ['user_name' => $product_owner->name, 'user_email' => $product_owner->email]) }}
                            <a href="{{ route('admin.users.edit', ['user' => $product_owner->id]) }}" class="alert-link" target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                                {{ __('products.edit-owner-alert-view-profile') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <span class="text-lg text-gray-800">{{ __('products.product-status') }}: </span>
                    @if($product->product_status == \App\Product::STATUS_PENDING)
                        <span class="text-warning">{{ __('products.product-status-pending') }}</span>
                    @elseif($product->product_status == \App\Product::STATUS_APPROVED)
                        <span class="text-success">{{ __('products.product-status-approved') }}</span>
                    @elseif($product->product_status == \App\Product::STATUS_SUSPEND)
                        <span class="text-danger">{{ __('products.product-status-suspend') }}</span>
                    @endif
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">

                    @if($product->product_status == \App\Product::STATUS_PENDING)
                        <form class="float-left pr-1" action="{{ route('admin.product.approve', ['product' => $product]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="far fa-check-circle"></i>
                                {{ __('backend.shared.approve') }}
                            </button>
                        </form>

                        <form class="float-left pr-1" action="{{ route('admin.product.suspend', ['product' => $product]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="far fa-flag"></i>
                                {{ __('backend.shared.suspend') }}
                            </button>
                        </form>
                    @endif

                    @if($product->product_status == \App\Product::STATUS_APPROVED)
                        <form class="float-left pr-1" action="{{ route('admin.product.disapprove', ['product' => $product]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-warning">
                                <i class="far fa-times-circle"></i>
                                {{ __('backend.shared.disapprove') }}
                            </button>
                        </form>

                        <form class="float-left pr-1" action="{{ route('admin.product.suspend', ['product' => $product]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="far fa-flag"></i>
                                {{ __('backend.shared.suspend') }}
                            </button>
                        </form>
                    @endif

                    @if($product->product_status == \App\Product::STATUS_SUSPEND)
                        <form class="float-left pr-1" action="{{ route('admin.product.approve', ['product' => $product]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="far fa-check-circle"></i>
                                {{ __('backend.shared.approve') }}
                            </button>
                        </form>

                        <form class="float-left pr-1" action="{{ route('admin.product.disapprove', ['product' => $product]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-warning">
                                <i class="far fa-times-circle"></i>
                                {{ __('backend.shared.disapprove') }}
                            </button>
                        </form>
                    @endif

                    <a class="btn btn-sm btn-outline-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash-alt"></i>
                        {{ __('products.delete-product') }}
                    </a>

                    <a class="btn btn-sm btn-primary" href="#" data-toggle="modal" data-target="#addAttributeModal">
                        <i class="fas fa-plus"></i>
                        {{ __('products.add-attributes') }}
                    </a>

                </div>
            </div>

            <hr>

            <div class="row mt-4">
                <div class="col-xl-12 col-lg-12 col-sm-12">
                    <form method="POST" action="{{ route('admin.products.update', ['product' => $product]) }}" class="">
                        @csrf
                        @method('PUT')
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label for="product_name" class="text-black">{{ __('products.form-product-name') }}</label>
                                <input id="product_name" type="text" class="form-control @error('product_name') is-invalid @enderror" name="product_name" value="{{ old('product_name') ? old('product_name') : $product->product_name }}">
                                @error('product_name')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="product_price" class="text-black">{{ __('products.form-product-price') }}</label>
                                <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">{{ $setting_product_currency_symbol }}</div>
                                </div>
                                <input id="product_price" type="text" class="form-control @error('product_price') is-invalid @enderror" name="product_price" value="{{ old('product_price') ? old('product_price') : $product->product_price }}">
                                </div>
                                <small id="product_priceHelpBlock" class="form-text text-muted">
                                    {{ __('products.form-product-price-help') }}
                                </small>
                                @error('product_price')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="product_status" class="text-black">{{ __('products.form-product-status') }}</label>
                                <select class="custom-select" name="product_status">
                                    <option value="{{ \App\Product::STATUS_PENDING }}" {{ (old('product_status') ? old('product_status') : $product->product_status) == \App\Product::STATUS_PENDING ? 'selected' : '' }}>{{ __('products.product-status-pending') }}</option>
                                    <option value="{{ \App\Product::STATUS_APPROVED }}" {{ (old('product_status') ? old('product_status') : $product->product_status) == \App\Product::STATUS_APPROVED ? 'selected' : '' }}>{{ __('products.product-status-approved') }}</option>
                                    <option value="{{ \App\Product::STATUS_SUSPEND }}" {{ (old('product_status') ? old('product_status') : $product->product_status) == \App\Product::STATUS_SUSPEND ? 'selected' : '' }}>{{ __('products.product-status-suspend') }}</option>
                                </select>
                                @error('product_status')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="product_description" class="text-black">{{ __('products.form-product-description') }}</label>
                                <textarea class="form-control @error('product_description') is-invalid @enderror" id="product_description" rows="5" name="product_description">{{ old('product_description') ? old('product_description') : $product->product_description }}</textarea>
                                @error('product_description')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <span class="text-lg text-gray-800">{{ __('products.form-product-image') }}</span>
                                <small class="form-text text-muted">
                                    {{ __('products.form-product-image-help') }}
                                </small>
                                @error('feature_image')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="row mt-3">
                                    <div class="col-8">
                                        <button id="upload_image" type="button" class="btn btn-primary btn-block mb-2">{{ __('products.form-product-image-select-image') }}</button>
                                        @if(empty($product->product_image_medium))
                                            <img id="image_preview" src="{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}" class="img-responsive">
                                        @else
                                            <img id="image_preview" src="{{ Storage::disk('public')->url('product/'. $product->product_image_medium) }}" class="img-responsive">
                                        @endif
                                        <input id="feature_image" type="hidden" name="feature_image">
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-8">
                                        <a class="btn btn-danger btn-block text-white" id="delete_feature_image_button">
                                            <i class="fas fa-trash-alt"></i>
                                            {{ __('role_permission.item.delete-feature-image') }}
                                        </a>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <span class="text-lg text-gray-800">{{ __('products.form-product-gallery-images') }}</span>
                                <small class="form-text text-muted">
                                    {{ __('products.form-product-gallery-images-help', ['gallery_photos_count' => $setting_product_max_gallery_photos]) }}
                                </small>
                                @error('image_gallery')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button id="upload_gallery" type="button" class="btn btn-primary btn-block mb-2">{{ __('products.form-product-gallery-images-select-images') }}</button>
                                        <div class="row" id="selected-images">
                                            @foreach($product->productGalleries as $key => $gallery)
                                                <div class="col-3 mb-2" id="item_image_gallery_{{ $gallery->id }}">
                                                    <img class="item_image_gallery_img" src="{{ Storage::disk('public')->url('product/gallery/'. $gallery->product_image_gallery_thumb_name) }}">
                                                    <br/><button class="btn btn-danger btn-sm text-white mt-1" onclick="$(this).attr('disabled', true); deleteGallery({{ $gallery->id }});">{{ __('backend.shared.delete') }}</button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row form-group mb-3">
                            <div class="col-md-12">
                                <span class="text-lg text-gray-800">{{ __('product_attributes.product-attribute') }}</span>
                                <small class="form-text text-muted">
                                    <a class="" href="#" data-toggle="modal" data-target="#addAttributeModal">
                                        <i class="fas fa-plus"></i>
                                        {{ __('products.add-attributes') }}
                                    </a>
                                </small>
                            </div>
                        </div>

                        @foreach($product_features as $key => $product_feature)

                            <div class="row form-group align-items-center border-left-info {{ $key%2 == 0 ? 'bg-light' : '' }} pt-3 pb-3">
                                <div class="col-xl-8 col-lg-6 col-md-6 col-sm-12">

                                    @if($product_feature->attribute->attribute_type == \App\Attribute::TYPE_TEXT)
                                        <label for="{{ str_slug('product_feature_' . $product_feature->id) }}" class="text-black">{{ $product_feature->attribute->attribute_name }}</label>
                                        <textarea class="form-control @error(str_slug('product_feature_' . $product_feature->id)) is-invalid @enderror" id="{{ str_slug('product_feature_' . $product_feature->id) }}" rows="5" name="{{ str_slug('product_feature_' . $product_feature->id) }}">{{ old(str_slug('product_feature_' . $product_feature->id)) ? old(str_slug('product_feature_' . $product_feature->id)) : $product_feature->product_feature_value }}</textarea>
                                        @error(str_slug('product_feature_' . $product_feature->id))
                                        <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                        @enderror
                                    @endif

                                    @if($product_feature->attribute->attribute_type == \App\Attribute::TYPE_SELECT)
                                        <label for="{{ str_slug('product_feature_' . $product_feature->id) }}" class="text-black">{{ $product_feature->attribute->attribute_name }}</label>
                                        <select class="custom-select" name="{{ str_slug('product_feature_' . $product_feature->id) }}" id="{{ str_slug('product_feature_' . $product_feature->id) }}">
                                            @foreach(explode(',', $product_feature->attribute->attribute_seed_value) as $key => $attribute_value)
                                                <option {{ $product_feature->product_feature_value == trim($attribute_value) ? 'selected' : '' }} value="{{ $attribute_value }}">{{ $attribute_value }}</option>
                                            @endforeach
                                        </select>
                                        @error(str_slug('product_feature_' . $product_feature->id))
                                        <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                        @enderror
                                    @endif

                                    @if($product_feature->attribute->attribute_type == \App\Attribute::TYPE_MULTI_SELECT)
                                        <label for="{{ str_slug('product_feature_' . $product_feature->id) }}" class="text-black">{{ $product_feature->attribute->attribute_name }}</label>
                                        <select multiple class="custom-select" name="{{ str_slug('product_feature_' . $product_feature->id) }}[]" id="{{ str_slug('product_feature_' . $product_feature->id) }}">
                                            @foreach(explode(',', $product_feature->attribute->attribute_seed_value) as $key => $attribute_value)
                                                <option {{ ( ($product_feature->product_feature_value == trim($attribute_value) ? true : false) || (strpos($product_feature->product_feature_value, trim($attribute_value) . ',') === 0 ? true : false) || (strpos($product_feature->product_feature_value, ', ' . trim($attribute_value) . ',') !== false ? true : false) || (strpos($product_feature->product_feature_value, ',' . trim($attribute_value) . ',') !== false ? true : false) || (strpos($product_feature->product_feature_value, ', ' . trim($attribute_value) ) !== false ? true : false) || (strpos($product_feature->product_feature_value, ',' . trim($attribute_value) ) !== false ? true : false) ) == true ? 'selected' : '' }} value="{{ $attribute_value }}">{{ $attribute_value }}</option>
                                            @endforeach
                                        </select>
                                        @error(str_slug('product_feature_' . $product_feature->id))
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    @endif

                                    @if($product_feature->attribute->attribute_type == \App\Attribute::TYPE_LINK)
                                        <label for="{{ str_slug('product_feature_' . $product_feature->id) }}" class="text-black">{{ $product_feature->attribute->attribute_name }}</label>
                                        <input id="{{ str_slug('product_feature_' . $product_feature->id) }}" type="text" class="form-control @error(str_slug('product_feature_' . $product_feature->id)) is-invalid @enderror" name="{{ str_slug('product_feature_' . $product_feature->id) }}" value="{{ old(str_slug('product_feature_' . $product_feature->id)) ? old(str_slug('product_feature_' . $product_feature->id)) : $product_feature->product_feature_value }}" aria-describedby="linkHelpBlock">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error(str_slug('product_feature_' . $product_feature->id))
                                        <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                        @enderror
                                    @endif

                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 pt-2">

                                    <a onclick="$('#form_feature_rank_up_{{ $product_feature->id }}').submit();" class="btn btn-primary btn-sm text-white">
                                        <i class="fas fa-arrow-up"></i>
                                    </a>

                                    <a onclick="$('#form_feature_rank_down_{{ $product_feature->id }}').submit();" class="btn btn-primary btn-sm text-white">
                                        <i class="fas fa-arrow-down"></i>
                                    </a>

                                    <a class="btn btn-danger btn-sm text-white" href="#" data-toggle="modal" data-target="#form_feature_delete_{{ $product_feature->id }}">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </div>

                            </div>
                        @endforeach

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

    <!-- Start forms for product features -->
    @foreach($product_features as $key => $product_feature)

        <form id="form_feature_rank_up_{{ $product_feature->id }}" action="{{ route('admin.product.feature.up', ['product' => $product->id, 'product_feature' => $product_feature->id]) }}" method="POST">
            @csrf
            @method('PUT')
        </form>

        <form id="form_feature_rank_down_{{ $product_feature->id }}" action="{{ route('admin.product.feature.down', ['product' => $product->id, 'product_feature' => $product_feature->id]) }}" method="POST">
            @csrf
            @method('PUT')
        </form>

        <!-- Modal - product feature delete -->
        <div class="modal fade" id="form_feature_delete_{{ $product_feature->id }}" tabindex="-1" role="dialog" aria-labelledby="form_feature_delete_{{ $product_feature->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ __('backend.shared.delete-message', ['record_type' => __('products.product-feature'), 'record_name' => $product_feature->attribute()->first()->attribute_name]) }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                        <form action="{{ route('admin.product.feature.destroy', ['product' => $product->id, 'product_feature' => $product_feature->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- End forms for product features -->



    <!-- Modal - feature image -->
    <div class="modal fade" id="image-crop-modal" tabindex="-1" role="dialog" aria-labelledby="image-crop-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('products.crop-feature-image') }}</h5>
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
                                <label class="custom-file-label" for="upload_image_input">{{ __('products.choose-image') }}</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button id="crop_image" type="button" class="btn btn-primary">{{ __('products.crop-image') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - delete -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('backend.shared.delete-message', ['record_type' => __('products.product'), 'record_name' => $product->product_name]) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.products.destroy', ['product' => $product->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal add attributes -->
    <div class="modal fade" id="addAttributeModal" tabindex="-1" role="dialog" aria-labelledby="addAttributeModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('products.modal-add-attribute-title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                @if($attributes->count() > 0)


                        <div class="modal-body">
                            <form action="{{ route('admin.product.attribute.update', ['product' => $product]) }}" method="POST" id="add-product-attribute-form">
                                @csrf
                                @method('PUT')
                                <select multiple size="{{ $attributes->count() + 5 }}" class="custom-select" name="attribute[]" id="attribute">
                                @foreach($attributes as $key => $attribute)
                                    <option value="{{ $attribute->id }}">
                                        {{ $attribute->attribute_name . ' / ' }}

                                        @if($attribute->attribute_type == \App\Attribute::TYPE_TEXT)
                                            {{ __('product_attributes.type-text') }}
                                        @elseif($attribute->attribute_type == \App\Attribute::TYPE_SELECT)
                                            {{ __('product_attributes.type-select') }}
                                        @elseif($attribute->attribute_type == \App\Attribute::TYPE_MULTI_SELECT)
                                            {{ __('product_attributes.type-multi-select') }}
                                        @elseif($attribute->attribute_type == \App\Attribute::TYPE_LINK)
                                            {{ __('product_attributes.type-link') }}
                                        @endif

                                        @if($attribute->attribute_type == \App\Attribute::TYPE_SELECT || $attribute->attribute_type == \App\Attribute::TYPE_MULTI_SELECT)
                                            {{ ' / ' . $attribute->attribute_seed_value }}
                                        @endif
                                    </option>
                                @endforeach
                                </select>
                                @error('attribute')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </form>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                            <button type="button" class="btn btn-success" id="add-product-attribute-button">{{ __('products.modal-add-attribute-button') }}</button>
                        </div>

                @else
                    <div class="modal-body">
                        {{ __('products.no-attributes') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    </div>
                @endif
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <!-- Image Crop Plugin Js -->
    <script src="{{ asset('backend/vendor/croppie/croppie.js') }}"></script>

    <!-- Bootstrap Fd Plugin Js-->
    <script src="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.js') }}"></script>

    <script>

        function deleteGallery(domId)
        {
            //$("form :submit").attr("disabled", true);

            var ajax_url = '/ajax/product/gallery/delete/' + domId;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: ajax_url,
                method: 'post',
                data: {
                },
                success: function(result){
                    console.log(result);
                    $('#item_image_gallery_' + domId).remove();
                }});
        }

        $(document).ready(function() {

            "use strict";

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

            if(window_width >= 800)
            {
                viewport_width = 455;
                viewport_height = 390;
            }
            else
            {
                viewport_width = window_width * 0.8;
                viewport_height = (viewport_width * 390) / 455;
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
                    $('#feature_image').val(response);
                    $('#image_preview').attr("src", response);
                });

                $('#image-crop-modal').modal('hide')
            });
            /**
             * End the croppie image plugin
             */


            /**
             * Start image gallery uplaod
             */
            $('#upload_gallery').on('click', function(){
                window.selectedImages = [];

                $.FileDialog({
                    accept: "image/jpeg",
                }).on("files.bs.filedialog", function (event) {
                    var html = "";
                    for (var a = 0; a < event.files.length; a++) {

                        if(a == 12) {break;}
                        selectedImages.push(event.files[a]);
                        html += "<div class='col-3 mb-2' id='item_image_gallery_" + a + "'>" +
                            "<img style='max-width: 120px;' src='" + event.files[a].content + "'>" +
                            "<br/><button class='btn btn-danger btn-sm text-white mt-1' onclick='$(\"#item_image_gallery_" + a + "\").remove();'>Delete</button>" +
                            "<input type='hidden' value='" + event.files[a].content + "' name='image_gallery[]'>" +
                            "</div>";
                    }
                    document.getElementById("selected-images").innerHTML += html;
                });
            });
            /**
             * End image gallery uplaod
             */


            /**
             * Start add product attribute modal form submit
             */
            $('#add-product-attribute-button').on('click', function(){
                $('#add-product-attribute-button').attr("disabled", true);
                $('#add-product-attribute-form').submit();
            });
            @error('attribute')
            $('#addAttributeModal').modal('show');
            @enderror
            /**
             * End add product attribute modal form submit
             */

            /**
             * Start delete feature image button
             */
            $('#delete_feature_image_button').on('click', function(){

                $('#delete_feature_image_button').attr("disabled", true);

                var ajax_url = '/ajax/product/image/delete/' + '{{ $product->id }}';

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    url: ajax_url,
                    method: 'post',
                    data: {
                    },
                    success: function(result){
                        console.log(result);

                        $('#image_preview').attr("src", "{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}");
                        $('#feature_image').val("");

                        $('#delete_feature_image_button').attr("disabled", false);
                    }});
            });
            /**
             * End delete feature image button
             */

        });
    </script>
@endsection

@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('item_section.edit') . ': ' . $item_section->item_section_title }}</h1>
            <p class="mb-4">{{ __('item_section.edit-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.items.sections.index', ['item' => $item]) }}" class="btn btn-info btn-icon-split">
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
            <div class="row mb-5">
                <div class="col-3">
                    @if(empty($item->item_image))
                        <img id="image_preview" src="{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}" class="img-responsive rounded">
                    @else
                        <img id="image_preview" src="{{ Storage::disk('public')->url('item/'. $item->item_image) }}" class="img-responsive rounded">
                    @endif

                    <a target="_blank" href="{{ route('page.item', $item->item_slug) }}" class="btn btn-primary btn-block mt-2">{{ __('backend.message.view-listing') }}</a>

                </div>
                <div class="col-9">
                    <p>
                        @foreach($item->allCategories()->get() as $key => $category)
                            <span class="bg-info rounded text-white pl-2 pr-2 pt-1 pb-1 mr-1">
                                {{ $category->category_name }}
                            </span>
                        @endforeach
                    </p>
                    <h1 class="h4 mb-2 text-gray-800">{{ $item->item_title }}</h1>

                    @if($item_has_claimed)
                        <p>
                            <i class="fas fa-check-circle"></i>
                            {{ __('item_claim.item-claimed-by') . " " . $item_claimed_user->name }}
                        </p>
                    @else
                        <p>
                            <i class="fas fa-question-circle"></i>
                            {{ __('item_claim.unclaimed') . ", " . __('item_claim.item-posted-by') . " " . $item->user->name }}
                        </p>
                    @endif

                    <p>
                        @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                        {{ $item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE ? $item->item_address . ', ' : '' }} {{ $item->city->city_name . ', ' . $item->state->state_name . ' ' . $item->item_postal_code }}
                        @else
                            <span class="bg-primary text-white pl-1 pr-1 rounded">{{ __('theme_directory_hub.online-listing.online-listing') }}</span>
                        @endif
                    </p>
                    <hr/>
                    <p>{{ $item->item_description }}</p>
                </div>
            </div>
            <hr>

            <div class="row mb-2">
                <div class="col-12">
                    <span class="text-gray-800">{{ __('item_section.edit-section-cap') }}</span>
                    |
                    <span class="text-gray-800">{{ $item_section->item_section_title }}</span>
                    |
                    @if($item_section->item_section_status == \App\ItemSection::STATUS_DRAFT)
                        <span class="bg-warning pl-1 pr-1 text-white text-sm rounded">
                            {{ __('item_section.item-section-status-draft') }}
                        </span>
                    @elseif($item_section->item_section_status == \App\ItemSection::STATUS_PUBLISHED)
                        <span class="bg-success pl-1 pr-1 text-white text-sm rounded">
                            {{ __('item_section.item-section-status-published') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.items.sections.update', ['item' => $item, 'item_section' => $item_section]) }}">
                        @csrf
                        @method('PUT')
                        <div class="row form-group">
                            <div class="col-md-4 col-sm-12">
                                <label for="item_section_title" class="text-black">{{ __('item_section.item-section-title') }}</label>
                                <input id="item_section_title" type="text" class="form-control @error('item_section_title') is-invalid @enderror" name="item_section_title" value="{{ old('item_section_title') ? old('item_section_title') : $item_section->item_section_title }}">
                                @error('item_section_title')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <label for="item_section_position" class="text-black">{{ __('item_section.item-section-position') }}</label>
                                <select class="custom-select" name="item_section_position">
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_BREADCRUMB }}" {{ (old('item_section_position') ? old('item_section_position') : $item_section->item_section_position) == \App\ItemSection::POSITION_AFTER_BREADCRUMB ? 'selected' : '' }}>{{ __('item_section.position-after-breadcrumb') }}</option>
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_GALLERY }}" {{ (old('item_section_position') ? old('item_section_position') : $item_section->item_section_position) == \App\ItemSection::POSITION_AFTER_GALLERY ? 'selected' : '' }}>{{ __('item_section.position-after-gallery') }}</option>
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_DESCRIPTION }}" {{ (old('item_section_position') ? old('item_section_position') : $item_section->item_section_position) == \App\ItemSection::POSITION_AFTER_DESCRIPTION ? 'selected' : '' }}>{{ __('item_section.position-after-description') }}</option>
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_LOCATION_MAP }}" {{ (old('item_section_position') ? old('item_section_position') : $item_section->item_section_position) == \App\ItemSection::POSITION_AFTER_LOCATION_MAP ? 'selected' : '' }}>{{ __('item_section.position-after-location-map') }}</option>
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_FEATURES }}" {{ (old('item_section_position') ? old('item_section_position') : $item_section->item_section_position) == \App\ItemSection::POSITION_AFTER_FEATURES ? 'selected' : '' }}>{{ __('item_section.position-after-features') }}</option>
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_REVIEWS }}" {{ (old('item_section_position') ? old('item_section_position') : $item_section->item_section_position) == \App\ItemSection::POSITION_AFTER_REVIEWS ? 'selected' : '' }}>{{ __('item_section.position-after-reviews') }}</option>
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_COMMENTS }}" {{ (old('item_section_position') ? old('item_section_position') : $item_section->item_section_position) == \App\ItemSection::POSITION_AFTER_COMMENTS ? 'selected' : '' }}>{{ __('item_section.position-after-comments') }}</option>
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_SHARE }}" {{ (old('item_section_position') ? old('item_section_position') : $item_section->item_section_position) == \App\ItemSection::POSITION_AFTER_SHARE ? 'selected' : '' }}>{{ __('item_section.position-after-share') }}</option>
                                </select>
                                @error('item_section_position')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <label for="item_section_status" class="text-black">{{ __('item_section.item-section-status') }}</label>
                                <select class="custom-select" name="item_section_status">
                                    <option value="{{ \App\ItemSection::STATUS_DRAFT }}" {{ (old('item_section_status') ? old('item_section_status') : $item_section->item_section_status) == \App\ItemSection::STATUS_DRAFT ? 'selected' : '' }}>{{ __('item_section.item-section-status-draft') }}</option>
                                    <option value="{{ \App\ItemSection::STATUS_PUBLISHED }}" {{ (old('item_section_status') ? old('item_section_status') : $item_section->item_section_status) == \App\ItemSection::STATUS_PUBLISHED ? 'selected' : '' }}>{{ __('item_section.item-section-status-published') }}</option>
                                </select>
                                @error('item_section_status')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group justify-content-between align-items-center">
                            <div class="col-8">
                                <button type="submit" class="btn btn-success text-white">
                                    {{ __('backend.shared.update') }}
                                </button>
                            </div>
                            <div class="col-4 text-right">
                                <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                                    {{ __('backend.shared.delete') }}
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <hr>

            <!-- Start collections section -->
            <div class="row mb-2">
                <div class="col-12">
                    <span class="text-gray-800">{{ __('item_section.manage-collections') }}</span>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <a class="" href="#" data-toggle="modal" data-target="#addProductModal">
                        <i class="fas fa-plus"></i>
                        {{ __('item_section.add-products') }}
                    </a>
                </div>
            </div>

            @if($all_item_section_collections->count() > 0)

                @foreach($all_item_section_collections as $all_item_section_collections_key => $collection)
                    <div class="row align-items-center pt-2 mb-2 border-left-info {{ $all_item_section_collections_key%2 == 0 ? 'bg-light' : '' }}">

                        @if($collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)

                            @php
                              $find_product = \App\Product::find($collection->item_section_collection_collectible_id);
                            @endphp

                            <div class="col-lg-2 col-md-2 col-sm-12">
                                @if(empty($find_product->product_image_small))
                                    <img src="{{ asset('backend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                @else
                                    <img src="{{ Storage::disk('public')->url('product/' . $find_product->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                @endif
                            </div>

                            <div class="col-lg-7 col-md-6 col-sm-12">
                                <span class="text-gray-800">{{ $find_product->product_name }}</span>
                                |
                                <span class="text-gray-800">{{ $product_currency_symbol . number_format($find_product->product_price, 2) }}</span>
                                |
                                @if($find_product->product_status == \App\Product::STATUS_PENDING)
                                    <span class="bg-warning pl-1 pr-1 text-white text-sm rounded">
                                        {{ __('products.product-status-pending') }}
                                    </span>
                                @elseif($find_product->product_status == \App\Product::STATUS_APPROVED)
                                    <span class="bg-success pl-1 pr-1 text-white text-sm rounded">
                                        {{ __('products.product-status-approved') }}
                                    </span>
                                @elseif($find_product->product_status == \App\Product::STATUS_SUSPEND)
                                    <span class="bg-danger pl-1 pr-1 text-white text-sm rounded">
                                        {{ __('products.product-status-suspend') }}
                                    </span>
                                @endif
                                |
                                <a target="_blank" class="ml-1" href="{{ route('admin.products.edit', ['product' => $collection->item_section_collection_collectible_id]) }}">
                                    <i class="fas fa-edit"></i>
                                    {{ __('item_section.edit-section-link') }}
                                </a>
                                <br>
                                <span>{{ str_limit($find_product->product_description, 100) }}</span>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <form class="float-left pr-1" action="{{ route('admin.items.sections.collections.rank.up', ['item' => $item, 'item_section' => $item_section, 'item_section_collection' => $collection->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary btn-sm text-white">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                </form>

                                <form class="float-left pr-1" action="{{ route('admin.items.sections.collections.rank.down', ['item' => $item, 'item_section' => $item_section, 'item_section_collection' => $collection->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary btn-sm text-white">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </form>

                                <a class="btn btn-danger btn-sm text-white" href="#" data-toggle="modal" data-target="#item_section_collection_delete_{{ $collection->id }}">
                                    <i class="far fa-trash-alt"></i>
                                </a>
                            </div>

                            <!-- Modal - collection delete -->
                            <div class="modal fade" id="item_section_collection_delete_{{ $collection->id }}" tabindex="-1" role="dialog" aria-labelledby="item_section_collection_delete_{{ $collection->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{ __('backend.shared.delete-message', ['record_type' => __('item_section.item-section-collection'), 'record_name' => $find_product->product_name]) }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                                            <form action="{{ route('admin.items.sections.collections.destroy', ['item' => $item, 'item_section' => $item_section, 'item_section_collection' => $collection->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif

                    </div>
                @endforeach

            @else
                <div class="row">
                    <div class="col-12">
                        <span>{{ __('item_section.section-no-collections') }}</span>
                    </div>
                </div>
            @endif

            <hr>
            <!-- End collections section -->


        </div>
    </div>

    <!-- Modal -->
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
                    {{ __('backend.shared.delete-message', ['record_type' => __('item_section.item-section'), 'record_name' => $item_section->item_section_title]) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.items.sections.destroy', ['item' => $item, 'item_section' => $item_section->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal add products -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('item_section.modal-add-attribute-title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                @if($available_products->count() > 0)


                    <div class="modal-body">
                        <form action="{{ route('admin.items.sections.collections.store', ['item' => $item, 'item_section' => $item_section]) }}" method="POST" id="add-product-form">
                            @csrf
                            <input type="hidden" name="item_section_collection_collectible_type" value="{{ \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT }}">

                            @foreach($available_products as $available_products_key => $available_product)
                                <div class="row align-items-center pt-2 mb-2 border-left-info {{ $available_products_key%2 == 0 ? 'bg-light' : '' }}">
                                    <div class="col-1">
                                        <input type="checkbox" name="item_section_collection_collectible_id[]" class="form-control" value="{{ $available_product->id }}">
                                    </div>
                                    <div class="col-3">
                                        @if(empty($available_product->product_image_small))
                                            <img src="{{ asset('backend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                        @else
                                            <img src="{{ Storage::disk('public')->url('product/' . $available_product->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                        @endif
                                    </div>
                                    <div class="col-8">
                                        <span class="text-gray-800">{{ $available_product->product_name }}</span>
                                        |
                                        <span class="text-gray-800">{{ $product_currency_symbol . number_format($available_product->product_price, 2) }}</span>
                                        |
                                        @if($available_product->product_status == \App\Product::STATUS_PENDING)
                                            <span class="bg-warning pl-1 pr-1 text-white text-sm rounded">
                                                {{ __('products.product-status-pending') }}
                                            </span>
                                        @elseif($available_product->product_status == \App\Product::STATUS_APPROVED)
                                            <span class="bg-success pl-1 pr-1 text-white text-sm rounded">
                                                {{ __('products.product-status-approved') }}
                                            </span>
                                        @elseif($available_product->product_status == \App\Product::STATUS_SUSPEND)
                                            <span class="bg-danger pl-1 pr-1 text-white text-sm rounded">
                                                {{ __('products.product-status-suspend') }}
                                            </span>
                                        @endif
                                        <br>
                                        <span>{{ str_limit($available_product->product_description, 30) }}</span>
                                    </div>
                                </div>
                            @endforeach

                            @error('item_section_collection_collectible_id')
                            <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                        <button type="button" class="btn btn-success" id="add-product-button">{{ __('item_section.modal-add-product-button') }}</button>
                    </div>

                @else
                    <div class="modal-body">
                        {{ __('item_section.no-products') }}
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

    <script>
        $(document).ready(function() {

            "use strict";

            /**
             * Start add product modal form submit
             */
            $('#add-product-button').on('click', function(){
                $('#add-product-button').attr("disabled", true);
                $('#add-product-form').submit();
            });
            @error('attribute')
            $('#addProductModal').modal('show');
            @enderror
            /**
             * End add product modal form submit
             */

        });
    </script>
@endsection

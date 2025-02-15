@extends('backend.user.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('products.index') }}</h1>
            <p class="mb-4">{{ __('products.index-user-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('user.products.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('products.add-product') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row mb-4">
                <div class="col-12">
                    <div class="row mb-2">
                        <div class="col-12"><span class="text-lg">{{ __('backend.shared.data-filter') }}</span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <form class="form-inline" action="{{ route('user.products.index') }}" method="GET">
                                <div class="form-group mr-2">
                                    <select class="custom-select" name="show_products_status">
                                        <option value="0" {{ empty($show_products_status) ? 'selected' : '' }}>{{ __('products.show-all-status') }}</option>

                                        <option value="{{ \App\Product::STATUS_PENDING }}" {{ $show_products_status == \App\Product::STATUS_PENDING ? 'selected' : '' }}>{{ __('products.status-pending') }}</option>
                                        <option value="{{ \App\Product::STATUS_APPROVED }}" {{ $show_products_status == \App\Product::STATUS_APPROVED ? 'selected' : '' }}>{{ __('products.status-approved') }}</option>
                                        <option value="{{ \App\Product::STATUS_SUSPEND }}" {{ $show_products_status == \App\Product::STATUS_SUSPEND ? 'selected' : '' }}>{{ __('products.status-suspend') }}</option>

                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">{{ __('backend.shared.update') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{ __('products.product-image') }}</th>
                                <th>{{ __('products.product-name') }}</th>
                                <th>{{ __('products.product-description') }}</th>
                                <th>{{ __('products.product-price') }}</th>
                                <th>{{ __('products.product-status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('products.product-image') }}</th>
                                <th>{{ __('products.product-name') }}</th>
                                <th>{{ __('products.product-description') }}</th>
                                <th>{{ __('products.product-price') }}</th>
                                <th>{{ __('products.product-status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_products as $key => $product)
                                <tr>
                                    <td>
                                        @if(empty($product->product_image_small))
                                            <img src="{{ asset('backend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                        @else
                                            <img src="{{ Storage::disk('public')->url('product/' . $product->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                        @endif
                                    </td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ str_limit($product->product_description, 200) }}</td>
                                    <td>
                                        @if(!empty($product->product_price))
                                            {{ $setting_product_currency_symbol . number_format($product->product_price, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->product_status == \App\Product::STATUS_PENDING)
                                            <span class="text-warning">{{ __('products.product-status-pending') }}</span>
                                        @elseif($product->product_status == \App\Product::STATUS_APPROVED)
                                            <span class="text-success">{{ __('products.product-status-approved') }}</span>
                                        @elseif($product->product_status == \App\Product::STATUS_SUSPEND)
                                            <span class="text-danger">{{ __('products.product-status-suspend') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('user.products.edit', ['product' => $product->id]) }}" class="btn btn-primary btn-circle">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function() {

            "use strict";

            $('#dataTable').DataTable();
        });
    </script>
@endsection

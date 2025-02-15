@extends('backend.user.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('product_attributes.index-user') }}</h1>
            <p class="mb-4">{{ __('product_attributes.index-user-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('user.attributes.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('product_attributes.add-attribute') }}</span>
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
                            <form class="form-inline" action="{{ route('user.attributes.index') }}" method="GET">
                                <div class="form-group mr-2">
                                    <select class="custom-select" name="show_attributes_type">
                                        <option value="0" {{ empty($show_attributes_type) ? 'selected' : '' }}>{{ __('product_attributes.show-all-types') }}</option>

                                        <option value="{{ \App\Attribute::TYPE_TEXT }}" {{ $show_attributes_type == \App\Attribute::TYPE_TEXT ? 'selected' : '' }}>{{ __('product_attributes.type-text') }}</option>
                                        <option value="{{ \App\Attribute::TYPE_SELECT }}" {{ $show_attributes_type == \App\Attribute::TYPE_SELECT ? 'selected' : '' }}>{{ __('product_attributes.type-select') }}</option>
                                        <option value="{{ \App\Attribute::TYPE_MULTI_SELECT }}" {{ $show_attributes_type == \App\Attribute::TYPE_MULTI_SELECT ? 'selected' : '' }}>{{ __('product_attributes.type-multi-select') }}</option>
                                        <option value="{{ \App\Attribute::TYPE_LINK }}" {{ $show_attributes_type == \App\Attribute::TYPE_LINK ? 'selected' : '' }}>{{ __('product_attributes.type-link') }}</option>
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
                                <th>{{ __('product_attributes.attribute-name') }}</th>
                                <th>{{ __('product_attributes.attribute-type') }}</th>
                                <th>{{ __('product_attributes.attribute-seed-value') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('product_attributes.attribute-name') }}</th>
                                <th>{{ __('product_attributes.attribute-type') }}</th>
                                <th>{{ __('product_attributes.attribute-seed-value') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_attributes as $key => $attribute)
                                <tr>
                                    <td>{{ $attribute->attribute_name }}</td>
                                    <td>
                                        @if($attribute->attribute_type == \App\Attribute::TYPE_TEXT)
                                            {{ __('product_attributes.type-text') }}
                                        @elseif($attribute->attribute_type == \App\Attribute::TYPE_SELECT)
                                            {{ __('product_attributes.type-select') }}
                                        @elseif($attribute->attribute_type == \App\Attribute::TYPE_MULTI_SELECT)
                                            {{ __('product_attributes.type-multi-select') }}
                                        @elseif($attribute->attribute_type == \App\Attribute::TYPE_LINK)
                                            {{ __('product_attributes.type-link') }}
                                        @endif
                                    </td>
                                    <td>{{ $attribute->attribute_seed_value }}</td>
                                    <td>
                                        <a href="{{ route('user.attributes.edit', ['attribute' => $attribute->id]) }}" class="btn btn-primary btn-circle">
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

@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.item.saved-item') }}</h1>
            <p class="mb-4">{{ __('backend.item.saved-item-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.items.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.item.add-item') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{ __('backend.item.feature-image') }}</th>
                                <th>{{ __('backend.category.category') }}</th>
                                <th>{{ __('backend.item.title') }}</th>
                                <th>{{ __('backend.item.address') }}</th>
                                <th>{{ __('backend.city.city') }}</th>
                                <th>{{ __('backend.state.state') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('backend.item.feature-image') }}</th>
                                <th>{{ __('backend.category.category') }}</th>
                                <th>{{ __('backend.item.title') }}</th>
                                <th>{{ __('backend.item.address') }}</th>
                                <th>{{ __('backend.city.city') }}</th>
                                <th>{{ __('backend.state.state') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($saved_items as $key => $item)
                                <tr>
                                    <td>
                                        @if(!empty($item->item_image_tiny))
                                            <img src="{{ Storage::disk('public')->url('item/' . $item->item_image_tiny) }}" alt="Image" class="img-fluid rounded">
                                        @elseif(!empty($item->item_image))
                                            <img src="{{ Storage::disk('public')->url('item/' . $item->item_image) }}" alt="Image" class="img-fluid rounded">
                                        @else
                                            <img src="{{ asset('backend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $item_categories = $item->allCategories()->get();
                                        @endphp
                                        @foreach($item_categories as $item_categories_key => $category)
                                            @if($item_categories->count() == $item_categories_key + 1)
                                                {{ $category->category_name }}
                                            @else
                                                {{ $category->category_name . ", " }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $item->item_title }}</td>

                                    <td>{{ $item->item_type == \App\Item::ITEM_TYPE_REGULAR ? $item->item_address : '' }}</td>
                                    <td>{{ $item->item_type == \App\Item::ITEM_TYPE_REGULAR ? $item->city->city_name : '' }}</td>
                                    <td>{{ $item->item_type == \App\Item::ITEM_TYPE_REGULAR ? $item->state->state_name : '' }}</td>

                                    <td>
                                        <a href="{{ route('page.item', $item->item_slug) }}" class="btn btn-sm btn-primary mb-1 rounded-circle" target="_blank">
                                            <i class="fas fa-search"></i>
                                        </a>
                                        <a class="btn btn-sm mb-1 btn-secondary rounded-circle text-white saved-item-remove-button" id="saved-item-remove-button-{{ $item->id }}"><i class="far fa-trash-alt"></i></a>
                                        <form id="saved-item-remove-button-{{ $item->id }}-form" action="{{ route('admin.items.unsave', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                                            @csrf
                                        </form>
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

            $('.saved-item-remove-button').on('click', function(){
                $(this).addClass("disabled");
                $("#" + $(this).attr('id') + "-form").submit();
            });
        });
    </script>
@endsection

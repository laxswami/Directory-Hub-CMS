@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('importer_csv.import-listing-index') }}</h1>
            <p class="mb-4">{{ __('importer_csv.import-listing-index-desc') }}</p>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row border-left-info bg-light pt-3">
                <div class="col-12">

                    <form class="" action="{{ route('admin.importer.item.data.index') }}" method="GET">
                        <div class="row form-group">
                            <div class="col-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="import_item_data_process_status[]" id="import_item_data_process_status_not_processed" value="{{ \App\ImportItemData::PROCESS_STATUS_NOT_PROCESSED }}" {{ in_array(\App\ImportItemData::PROCESS_STATUS_NOT_PROCESSED, $import_item_data_process_status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="import_item_data_process_status_not_processed">{{ __('importer_csv.import-listing-status-not-processed') }}</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="import_item_data_process_status[]" id="import_item_data_process_status_success_processed" value="{{ \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS }}" {{ in_array(\App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS, $import_item_data_process_status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="import_item_data_process_status_success_processed">{{ __('importer_csv.import-listing-status-success') }}</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="import_item_data_process_status[]" id="import_item_data_process_status_error_processed" value="{{ \App\ImportItemData::PROCESS_STATUS_PROCESSED_ERROR }}" {{ in_array(\App\ImportItemData::PROCESS_STATUS_PROCESSED_ERROR, $import_item_data_process_status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="import_item_data_process_status_error_processed">{{ __('importer_csv.import-listing-status-error') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12 col-md-4">
                                <select class="custom-select" name="selected_import_item_data_markup">
                                    <option value="" {{ empty($selected_import_item_data_markup) ? 'selected' : '' }}>{{ __('importer_csv.import-listing-markup-all') }}</option>
                                    @foreach($all_import_item_data_markup as $all_import_item_data_markup_key => $import_item_data_markup)
                                        <option value="{{ $import_item_data_markup->import_item_data_markup }}" {{ $selected_import_item_data_markup == $import_item_data_markup->import_item_data_markup ? 'selected' : '' }}>{{ $import_item_data_markup->import_item_data_markup }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <select class="custom-select" name="order_by">
                                    <option value="{{ \App\ImportItemData::ORDER_BY_ITEM_NEWEST_PROCESSED }}" {{ $order_by == \App\ImportItemData::ORDER_BY_ITEM_NEWEST_PROCESSED ? 'selected' : '' }}>{{ __('importer_csv.import-listing-order-newest-processed') }}</option>
                                    <option value="{{ \App\ImportItemData::ORDER_BY_ITEM_OLDEST_PROCESSED }}" {{ $order_by == \App\ImportItemData::ORDER_BY_ITEM_OLDEST_PROCESSED ? 'selected' : '' }}>{{ __('importer_csv.import-listing-order-oldest-processed') }}</option>
                                    <option value="{{ \App\ImportItemData::ORDER_BY_ITEM_NEWEST_PARSED }}" {{ $order_by == \App\ImportItemData::ORDER_BY_ITEM_NEWEST_PARSED ? 'selected' : '' }}>{{ __('importer_csv.import-listing-order-newest-parsed') }}</option>
                                    <option value="{{ \App\ImportItemData::ORDER_BY_ITEM_OLDEST_PARSED }}" {{ $order_by == \App\ImportItemData::ORDER_BY_ITEM_OLDEST_PARSED ? 'selected' : '' }}>{{ __('importer_csv.import-listing-order-oldest-parsed') }}</option>
                                    <option value="{{ \App\ImportItemData::ORDER_BY_ITEM_TITLE_A_Z }}" {{ $order_by == \App\ImportItemData::ORDER_BY_ITEM_TITLE_A_Z ? 'selected' : '' }}>{{ __('importer_csv.import-listing-order-title-a-z') }}</option>
                                    <option value="{{ \App\ImportItemData::ORDER_BY_ITEM_TITLE_Z_A }}" {{ $order_by == \App\ImportItemData::ORDER_BY_ITEM_TITLE_Z_A ? 'selected' : '' }}>{{ __('importer_csv.import-listing-order-title-z-a') }}</option>
                                    <option value="{{ \App\ImportItemData::ORDER_BY_ITEM_CITY_A_Z }}" {{ $order_by == \App\ImportItemData::ORDER_BY_ITEM_CITY_A_Z ? 'selected' : '' }}>{{ __('importer_csv.import-listing-order-city-a-z') }}</option>
                                    <option value="{{ \App\ImportItemData::ORDER_BY_ITEM_CITY_Z_A }}" {{ $order_by == \App\ImportItemData::ORDER_BY_ITEM_CITY_Z_A ? 'selected' : '' }}>{{ __('importer_csv.import-listing-order-city-z-a') }}</option>
                                    <option value="{{ \App\ImportItemData::ORDER_BY_ITEM_STATE_A_Z }}" {{ $order_by == \App\ImportItemData::ORDER_BY_ITEM_STATE_A_Z ? 'selected' : '' }}>{{ __('importer_csv.import-listing-order-state-a-z') }}</option>
                                    <option value="{{ \App\ImportItemData::ORDER_BY_ITEM_STATE_Z_A }}" {{ $order_by == \App\ImportItemData::ORDER_BY_ITEM_STATE_Z_A ? 'selected' : '' }}>{{ __('importer_csv.import-listing-order-state-z-a') }}</option>
                                    <option value="{{ \App\ImportItemData::ORDER_BY_ITEM_COUNTRY_A_Z }}" {{ $order_by == \App\ImportItemData::ORDER_BY_ITEM_COUNTRY_A_Z ? 'selected' : '' }}>{{ __('importer_csv.import-listing-order-country-a-z') }}</option>
                                    <option value="{{ \App\ImportItemData::ORDER_BY_ITEM_COUNTRY_Z_A }}" {{ $order_by == \App\ImportItemData::ORDER_BY_ITEM_COUNTRY_Z_A ? 'selected' : '' }}>{{ __('importer_csv.import-listing-order-country-z-a') }}</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <select class="custom-select" name="count_per_page">
                                    <option value="{{ \App\ImportItemData::COUNT_PER_PAGE_10 }}" {{ $count_per_page == \App\ImportItemData::COUNT_PER_PAGE_10 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-10') }}</option>
                                    <option value="{{ \App\ImportItemData::COUNT_PER_PAGE_25 }}" {{ $count_per_page == \App\ImportItemData::COUNT_PER_PAGE_25 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-25') }}</option>
                                    <option value="{{ \App\ImportItemData::COUNT_PER_PAGE_50 }}" {{ $count_per_page == \App\ImportItemData::COUNT_PER_PAGE_50 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-50') }}</option>
                                    <option value="{{ \App\ImportItemData::COUNT_PER_PAGE_100 }}" {{ $count_per_page == \App\ImportItemData::COUNT_PER_PAGE_100 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-100') }}</option>
                                    <option value="{{ \App\ImportItemData::COUNT_PER_PAGE_250 }}" {{ $count_per_page == \App\ImportItemData::COUNT_PER_PAGE_250 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-250') }}</option>
                                    <option value="{{ \App\ImportItemData::COUNT_PER_PAGE_500 }}" {{ $count_per_page == \App\ImportItemData::COUNT_PER_PAGE_500 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-500') }}</option>
                                    <option value="{{ \App\ImportItemData::COUNT_PER_PAGE_1000 }}" {{ $count_per_page == \App\ImportItemData::COUNT_PER_PAGE_1000 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-1000') }}</option>
                                </select>
                            </div>

                        </div>

                        <div class="row form-group">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary mr-2">{{ __('backend.shared.update') }}</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <div class="row pt-4">
                <div class="col-12 text-right">
                    {{ $all_import_item_data_count . ' ' . __('category_description.records') }}
                </div>
            </div>

            <hr class="mt-1">

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{ __('importer_csv.select') }}</th>
                                <th>{{ __('importer_csv.import-listing-markup') }}</th>
                                <th>{{ __('importer_csv.import-listing-title') }}</th>
                                <th>{{ __('importer_csv.import-listing-city') }}</th>
                                <th>{{ __('importer_csv.import-listing-state') }}</th>
                                <th>{{ __('importer_csv.import-listing-country') }}</th>
                                <th>{{ __('importer_csv.import-listing-status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('importer_csv.select') }}</th>
                                <th>{{ __('importer_csv.import-listing-markup') }}</th>
                                <th>{{ __('importer_csv.import-listing-title') }}</th>
                                <th>{{ __('importer_csv.import-listing-city') }}</th>
                                <th>{{ __('importer_csv.import-listing-state') }}</th>
                                <th>{{ __('importer_csv.import-listing-country') }}</th>
                                <th>{{ __('importer_csv.import-listing-status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_import_item_data as $all_import_item_data_key => $import_item_data)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            @if($import_item_data->import_item_data_process_status != \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS)
                                                <input class="form-check-input import_item_data_checkbox" type="checkbox" id="import_item_data_checkbox_{{ $import_item_data->id }}" value="{{ $import_item_data->id }}">
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $import_item_data->import_item_data_markup }}</td>
                                    <td>{{ $import_item_data->import_item_data_item_title }}</td>
                                    <td>{{ $import_item_data->import_item_data_city }}</td>
                                    <td>{{ $import_item_data->import_item_data_state }}</td>
                                    <td>{{ $import_item_data->import_item_data_country }}</td>
                                    <td>
                                        @if($import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_NOT_PROCESSED)
                                            <span class="text-warning">{{ __('importer_csv.import-listing-status-not-processed') }}</span>
                                        @elseif($import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS)
                                            <span class="text-success">{{ __('importer_csv.import-listing-status-success') }}</span>
                                        @elseif($import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_ERROR)
                                            <span class="text-danger">{{ __('importer_csv.import-listing-status-error') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ route('admin.importer.item.data.edit', ['import_item_data' => $import_item_data->id]) }}" class="btn btn-sm btn-primary">
                                            {{ __('importer_csv.import-listing-detail') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <hr class="mb-1">

            <div class="row">
                <div class="col-12 text-right">
                    {{ $all_import_item_data_count . ' ' . __('category_description.records') }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <button id="select_all_button" class="btn btn-sm btn-primary rounded text-white">
                        <i class="far fa-check-square"></i>
                        {{ __('importer_csv.import-listing-select-all') }}
                    </button>
                    <button id="un_select_all_button" class="btn btn-sm btn-primary rounded text-white">
                        <i class="far fa-square"></i>
                        {{ __('importer_csv.import-listing-un-select-all') }}
                    </button>
                    <button id="import_selected_button" class="btn btn-sm btn-info rounded text-white">
                        <i class="fas fa-hourglass-start"></i>
                        {{ __('importer_csv.import-listing-selected-button') }}
                    </button>
                    <button id="delete_selected_button" class="btn btn-sm btn-danger rounded text-white">
                        <i class="far fa-trash-alt"></i>
                        {{ __('importer_csv.import-listing-delete-selected') }}
                    </button>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <span class="text-danger" id="span_delete_selected_error"></span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    {{ $all_import_item_data->appends(['import_item_data_process_status' => $import_item_data_process_status, 'order_by' => $order_by])->links() }}
                </div>
            </div>

            <hr>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="errorNotifyModal" tabindex="-1" role="dialog" aria-labelledby="errorNotifyModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('importer_csv.error-notify-modal-close-title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
                        <span id="span_error_notify_modal"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('importer_csv.error-notify-modal-close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Selected Modal -->
    <div class="modal fade" id="import_selected_modal" tabindex="-1" role="dialog" aria-labelledby="import_selected_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('importer_csv.import-listing-selected-modal-title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row border-left-info">
                        <div class="col-12">

                            <div class="row mb-3">
                                <div class="col-12">
                                    <span class="text-gray-800 text-lg">{{ __('importer_csv.choose-import-listing-preference-selected') }}:</span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <span id="span_import_listing_modal_error" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    {{ __('importer_csv.choose-import-listing-categories') }}:
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    @foreach($all_categories as $all_categories_key => $category)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input available_category" type="checkbox" id="category_{{ $category['category_id'] }}" value="{{ $category['category_id'] }}">
                                            <label class="form-check-label" for="category_{{ $category['category_id'] }}">{{ $category['category_name'] }}</label>
                                        </div>
                                    @endforeach
                                    @error('category')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="user_id" class="text-black">{{ __('importer_csv.choose-import-listing-owner') }}</label>
                                    <select id="user_id" class="custom-select @error('user_id') is-invalid @enderror" name="user_id">

                                        <option value="{{ $admin_user->id }}">{{ __('products.myself') }}</option>
                                        <option value="{{ \App\ImportItemData::IMPORT_RANDOM_USER }}">{{ __('theme_directory_hub.importer.random-user') }}</option>

                                        @foreach($all_users as $key => $user)
                                            <option value="{{ $user->id }}">{{ $user->name . ' (' . $user->email . ')' }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="item_type" class="text-black">{{ __('theme_directory_hub.online-listing.listing-type') }}</label>
                                    <select id="item_type" class="custom-select @error('item_type') is-invalid @enderror" name="item_type">

                                        <option value="{{ \App\Item::ITEM_TYPE_REGULAR }}">{{ __('theme_directory_hub.online-listing.regular-listing') }}</option>
                                        <option value="{{ \App\Item::ITEM_TYPE_ONLINE }}">{{ __('theme_directory_hub.online-listing.online-listing') }}</option>

                                    </select>
                                    @error('item_type')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="item_status" class="text-black">{{ __('importer_csv.choose-import-listing-status') }}</label>
                                    <select id="item_status" class="custom-select @error('item_status') is-invalid @enderror" name="item_status">
                                        <option value="{{ \App\Item::ITEM_SUBMITTED }}">{{ __('backend.item.submitted') }}</option>
                                        <option value="{{ \App\Item::ITEM_PUBLISHED }}">{{ __('backend.item.published') }}</option>
                                        <option value="{{ \App\Item::ITEM_SUSPENDED }}">{{ __('backend.item.suspended') }}</option>
                                    </select>
                                    @error('item_status')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="item_featured" class="text-black">{{ __('importer_csv.choose-import-listing-featured') }}</label>
                                    <select id="item_featured" class="custom-select @error('item_featured') is-invalid @enderror" name="item_featured">
                                        <option value="{{ \App\Item::ITEM_NOT_FEATURED }}">{{ __('backend.shared.no') }}</option>
                                        <option value="{{ \App\Item::ITEM_FEATURED }}">{{ __('backend.shared.yes') }}</option>
                                    </select>
                                    @error('item_featured')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 text-right">
                                    <button class="btn btn-info text-white" id="start_import_selected_button">
                                        {{ __('importer_csv.import-listing-selected-button') }}
                                    </button>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-12">
                                    <div class="progress">
                                        <div id="div_import_progress_bar" class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">

                                    <div class="alert alert-info" role="alert">
                                        <span id="import_listing_selected_total_count">0</span>
                                        <span>{{ __('importer_csv.import-listing-selected-total') }}</span>
                                        (
                                        <span class="text-success" id="import_listing_selected_success_count">0</span>
                                        <span class="text-success">{{ __('importer_csv.import-listing-selected-success') }}</span>
                                        /
                                        <span class="text-danger" id="import_listing_selected_error_count">0</span>
                                        <span class="text-danger">{{ __('importer_csv.import-listing-selected-error') }}</span>
                                        )
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('importer_csv.error-notify-modal-close') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>

        $(document).ready(function() {

            "use strict";

            /**
             * Start select all button
             */
            $('#select_all_button').on('click', function () {

                $(".import_item_data_checkbox").each(function (index) {
                    $(this).prop('checked', true);
                });

            });
            /**
             * End select all button
             */

            /**
             * Start un-select all button
             */
            $('#un_select_all_button').on('click', function () {

                $(".import_item_data_checkbox").each(function (index) {
                    $(this).prop('checked', false);
                });

            });
            /**
             * End un-select all button
             */

            /**
             * Start import selected button
             */
            $('#import_selected_button').on('click', function () {

                var selected_import_listing_count = $(".import_item_data_checkbox:checked").length;

                if(selected_import_listing_count === 0)
                {
                    $("#span_error_notify_modal").text('{{ __('importer_csv.alert.import-process-no-listing-selected') }}');
                    $("#errorNotifyModal").modal('show');
                }
                else
                {
                    $("#import_listing_selected_total_count").text(selected_import_listing_count);
                    $("#import_selected_modal").modal('show');
                }
            });
            /**
             * End import selected button
             */

            /**
             * Start delete selected button
             */
            $('#delete_selected_button').on('click', function () {

                var selected_import_listing_count = $(".import_item_data_checkbox:checked").length;

                if(selected_import_listing_count === 0)
                {
                    $("#span_error_notify_modal").text('{{ __('importer_csv.alert.delete-import-listing-process-no-listing-selected') }}');
                    $("#errorNotifyModal").modal('show');
                }
                else
                {
                    $("#select_all_button").attr("disabled", true);
                    $("#un_select_all_button").attr("disabled", true);
                    $("#import_selected_button").attr("disabled", true);
                    $("#delete_selected_button").attr("disabled", true);
                    $("#delete_selected_button").text("{{ __('importer_csv.import-listing-delete-progress') }}");

                    var delete_selected_import_listing_progress = 0;


                    $(".import_item_data_checkbox:checked").each(function (index) {

                        var selected_import_listing_elm = $(this);

                        setTimeout(function() {

                            var selected_import_listing_id = selected_import_listing_elm.val();

                            var ajax_url = '/admin/importer/item/data/' + selected_import_listing_id + '/destroy-ajax';

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

                                    delete_selected_import_listing_progress += 1;

                                    $("#delete_selected_button").text("{{ __('importer_csv.import-listing-delete-progress') }}" + " " + delete_selected_import_listing_progress + " " + "{{ __('importer_csv.import-listing-delete-progress-deleted') }}");

                                    if(delete_selected_import_listing_progress === selected_import_listing_count)
                                    {
                                        $("#delete_selected_button").text("{{ __('importer_csv.import-listing-delete-complete') }}");
                                        location.reload();
                                    }

                                },
                                error: function(xhr){
                                    console.log("an error occured: " + xhr.status + " " + xhr.statusText);

                                    $("#span_delete_selected_error").text("{{ __('importer_csv.import-listing-delete-error') }}");
                                }
                            }); // end ajax

                        }, 2000); // end setTimeout

                    }); // end .import_item_data_checkbox:checked loop
                }
            });
            /**
             * End delete selected button
             */

            $('#start_import_selected_button').on('click', function () {

                /**
                 * Start selected listing import process
                 */
                $("#start_import_selected_button").attr("disabled", true);

                var selected_import_listing_count = $(".import_item_data_checkbox:checked").length;
                var success_import_listing_count = 0;
                var error_import_listing_count = 0;
                var selected_import_listing_progress = 0;

                $("#import_listing_selected_total_count").text(selected_import_listing_count);

                var selected_categories = [];

                $(".available_category:checked").each(function (index) {
                    selected_categories.push($(this).val());
                });

                if(selected_categories.length === 0)
                {
                    $("#span_import_listing_modal_error").text('{{ __('importer_csv.alert.import-process-no-categories-selected') }}');
                    $("#start_import_selected_button").attr("disabled", false);
                    return false;
                }
                else
                {
                    $('#start_import_selected_button').text('{{ __('importer_csv.import-listing-import-button-progress') }}');
                    $('#div_import_progress_bar').text('{{ __('importer_csv.alert.import-listing-process-in-progress') }}');
                    $("#span_import_listing_modal_error").text('');
                }

                var selected_user_id = $("#user_id").val();
                var selected_item_type = $("#item_type").val();
                var selected_item_status = $("#item_status").val();
                var selected_item_featured = $("#item_featured").val();

                $(".import_item_data_checkbox:checked").each(function (index) {

                    var selected_import_listing_elm = $(this);

                    setTimeout(function() {

                        var selected_import_listing_id = selected_import_listing_elm.val();

                        var ajax_url = '/admin/importer/item/data/' + selected_import_listing_id + '/import-ajax';

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        jQuery.ajax({
                            url: ajax_url,
                            method: 'post',
                            data: {
                                'user_id' : selected_user_id,
                                'category' : selected_categories,
                                'item_type' : selected_item_type,
                                'item_status' : selected_item_status,
                                'item_featured' : selected_item_featured,
                            },
                            success: function(result){

                                selected_import_listing_progress += 1;

                                if(result.status == {{ \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS }})
                                {
                                    success_import_listing_count += 1;
                                }
                                if(result.status == {{ \App\ImportItemData::PROCESS_STATUS_PROCESSED_ERROR }})
                                {
                                    error_import_listing_count += 1;
                                }

                                $("#import_listing_selected_success_count").text(success_import_listing_count);
                                $("#import_listing_selected_error_count").text(error_import_listing_count);

                                var progress_percentage = selected_import_listing_progress/selected_import_listing_count * 100;

                                $('#div_import_progress_bar').attr("aria-valuenow", progress_percentage);
                                $('#div_import_progress_bar').attr("style", "width: " + progress_percentage + "%;");

                                if(selected_import_listing_progress === selected_import_listing_count)
                                {
                                    $('#start_import_selected_button').text('{{ __('importer_csv.import-listing-import-button-complete') }}');
                                    $('#div_import_progress_bar').text('{{ __('importer_csv.alert.import-process-completed') }}');
                                }

                            },
                            error: function(xhr){
                                console.log("an error occured: " + xhr.status + " " + xhr.statusText);

                                $("#span_import_listing_modal_error").text("{{ __('importer_csv.import-listing-import-button-error') }}");
                            }
                        }); // end ajax

                    }, 2000); // end setTimeout

                }); // end .import_item_data_checkbox:checked each
                /**
                 * End selected listing import process
                 */

            }); // end import_selected button click event

        });
    </script>
@endsection

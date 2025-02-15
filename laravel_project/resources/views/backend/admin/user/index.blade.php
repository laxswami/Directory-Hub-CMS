@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.user.user') }}</h1>
            <p class="mb-4">{{ __('backend.user.user-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.users.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.user.add-user') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row border-left-info bg-light pt-3">
                <div class="col-12">

                    <form class="" action="{{ route('admin.users.index') }}" method="GET">

                        <div class="row form-group">
                            <div class="col-12">
                                <span class="text-gray-800">{{ __('backend.shared.data-filter') }}:</span>
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-12 col-md-2">
                                <select class="custom-select" name="user_email_verified">
                                    <option value="{{ \App\User::USER_EMAIL_VERIFIED }}" {{ $user_email_verified == \App\User::USER_EMAIL_VERIFIED ? 'selected' : '' }}>{{ __('admin_users_table.email-verified') }}</option>
                                    <option value="{{ \App\User::USER_EMAIL_NOT_VERIFIED }}" {{ $user_email_verified == \App\User::USER_EMAIL_NOT_VERIFIED ? 'selected' : '' }}>{{ __('admin_users_table.email-un-verified') }}</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <select class="custom-select" name="user_suspended">
                                    <option value="{{ \App\User::USER_NOT_SUSPENDED }}" {{ $user_suspended == \App\User::USER_NOT_SUSPENDED ? 'selected' : '' }}>{{ __('admin_users_table.status-active') }}</option>
                                    <option value="{{ \App\User::USER_SUSPENDED }}" {{ $user_suspended == \App\User::USER_SUSPENDED ? 'selected' : '' }}>{{ __('admin_users_table.status-suspend') }}</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <select class="custom-select" name="order_by">
                                    <option value="{{ \App\User::ORDER_BY_USER_NEWEST }}" {{ $order_by == \App\User::ORDER_BY_USER_NEWEST ? 'selected' : '' }}>{{ __('admin_users_table.user-order-by-newest') }}</option>
                                    <option value="{{ \App\User::ORDER_BY_USER_OLDEST }}" {{ $order_by == \App\User::ORDER_BY_USER_OLDEST ? 'selected' : '' }}>{{ __('admin_users_table.user-order-by-oldest') }}</option>
                                    <option value="{{ \App\User::ORDER_BY_USER_NAME_A_Z }}" {{ $order_by == \App\User::ORDER_BY_USER_NAME_A_Z ? 'selected' : '' }}>{{ __('admin_users_table.user-order-by-name-a-z') }}</option>
                                    <option value="{{ \App\User::ORDER_BY_USER_NAME_Z_A }}" {{ $order_by == \App\User::ORDER_BY_USER_NAME_Z_A ? 'selected' : '' }}>{{ __('admin_users_table.user-order-by-name-z-a') }}</option>
                                    <option value="{{ \App\User::ORDER_BY_USER_EMAIL_A_Z }}" {{ $order_by == \App\User::ORDER_BY_USER_EMAIL_A_Z ? 'selected' : '' }}>{{ __('admin_users_table.user-order-by-email-a-z') }}</option>
                                    <option value="{{ \App\User::ORDER_BY_USER_EMAIL_Z_A }}" {{ $order_by == \App\User::ORDER_BY_USER_EMAIL_Z_A ? 'selected' : '' }}>{{ __('admin_users_table.user-order-by-email-z-a') }}</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <select class="custom-select" name="count_per_page">
                                    <option value="{{ \App\User::COUNT_PER_PAGE_10 }}" {{ $count_per_page == \App\User::COUNT_PER_PAGE_10 ? 'selected' : '' }}>{{ __('admin_users_table.shared.count-per-page-10') }}</option>
                                    <option value="{{ \App\User::COUNT_PER_PAGE_25 }}" {{ $count_per_page == \App\User::COUNT_PER_PAGE_25 ? 'selected' : '' }}>{{ __('admin_users_table.shared.count-per-page-25') }}</option>
                                    <option value="{{ \App\User::COUNT_PER_PAGE_50 }}" {{ $count_per_page == \App\User::COUNT_PER_PAGE_50 ? 'selected' : '' }}>{{ __('admin_users_table.shared.count-per-page-50') }}</option>
                                    <option value="{{ \App\User::COUNT_PER_PAGE_100 }}" {{ $count_per_page == \App\User::COUNT_PER_PAGE_100 ? 'selected' : '' }}>{{ __('admin_users_table.shared.count-per-page-100') }}</option>
                                    <option value="{{ \App\User::COUNT_PER_PAGE_250 }}" {{ $count_per_page == \App\User::COUNT_PER_PAGE_250 ? 'selected' : '' }}>{{ __('admin_users_table.shared.count-per-page-250') }}</option>
                                    <option value="{{ \App\User::COUNT_PER_PAGE_500 }}" {{ $count_per_page == \App\User::COUNT_PER_PAGE_500 ? 'selected' : '' }}>{{ __('admin_users_table.shared.count-per-page-500') }}</option>
                                    <option value="{{ \App\User::COUNT_PER_PAGE_1000 }}" {{ $count_per_page == \App\User::COUNT_PER_PAGE_1000 ? 'selected' : '' }}>{{ __('admin_users_table.shared.count-per-page-1000') }}</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-primary mr-2">{{ __('backend.shared.update') }}</button>
                            </div>

                        </div>

                    </form>

                </div>
            </div>

            <div class="row pt-4">
                <div class="col-12">
                    {{ $all_users_count . ' ' . __('category_description.records') }}
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
                                <th>{{ __('backend.user.name') }}</th>
                                <th>{{ __('backend.user.email') }}</th>
                                <th>{{ __('backend.user.email-verified') }}</th>
                                <th>{{ __('backend.user.status') }}</th>
                                <th>{{ __('backend.user.created-at') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('importer_csv.select') }}</th>
                                <th>{{ __('backend.user.name') }}</th>
                                <th>{{ __('backend.user.email') }}</th>
                                <th>{{ __('backend.user.email-verified') }}</th>
                                <th>{{ __('backend.user.status') }}</th>
                                <th>{{ __('backend.user.created-at') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_users as $key => $user)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input users_table_index_checkbox" type="checkbox" id="users_table_index_checkbox_{{ $user->id }}" value="{{ $user->id }}">
                                            @if(empty($user->user_image))
                                                <img id="image_preview" src="{{ asset('backend/images/placeholder/profile-' . intval($user->id % 10) . '.webp') }}" class="img-responsive rounded">
                                            @else
                                                <img id="image_preview" src="{{ Storage::disk('public')->url('user/'. $user->user_image) }}" class="img-responsive rounded">
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if(empty($user->email_verified_at))
                                            <span class="rounded bg-warning text-white pl-2 pr-2 pt-1 pb-1">{{ __('backend.user.pending') }}</span>
                                        @else
                                            <span class="rounded bg-success text-white pl-2 pr-2 pt-1 pb-1">{{ __('backend.user.verified') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->user_suspended == \App\User::USER_SUSPENDED)
                                            <span class="rounded bg-warning text-white pl-2 pr-2 pt-1 pb-1">{{ __('backend.user.suspended') }}</span>
                                        @else
                                            <span class="rounded bg-success text-white pl-2 pr-2 pt-1 pb-1">{{ __('backend.user.active') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-circle mb-1">
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

            <hr class="mb-1">

            <div class="row mb-3">
                <div class="col-12">
                    {{ $all_users_count . ' ' . __('category_description.records') }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 col-md-8">
                    <button id="select_all_button" class="btn btn-sm btn-primary text-white">
                        <i class="far fa-check-square"></i>
                        {{ __('admin_users_table.shared.select-all') }}
                    </button>
                    <button id="un_select_all_button" class="btn btn-sm btn-primary text-white">
                        <i class="far fa-square"></i>
                        {{ __('admin_users_table.shared.un-select-all') }}
                    </button>
                </div>
                <div class="col-12 col-md-4 text-right">
                    <div class="dropdown">
                        <button class="btn btn-info btn-sm dropdown-toggle text-white" type="button" id="table_option_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-tasks"></i>
                            {{ __('admin_users_table.shared.options') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="table_option_dropdown">
                            <button class="dropdown-item" type="button" id="verify_selected_button">
                                <i class="far fa-check-circle"></i>
                                {{ __('admin_users_table.verify-selected') }}
                            </button>
                            <button class="dropdown-item" type="button" id="suspend_selected_button">
                                <i class="fas fa-lock"></i>
                                {{ __('admin_users_table.suspend-selected') }}
                            </button>
                            <button class="dropdown-item" type="button" id="un_lock_selected_button">
                                <i class="fas fa-unlock-alt"></i>
                                {{ __('admin_users_table.un-lock-selected') }}
                            </button>
                            <div class="dropdown-divider"></div>
                            <button class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#deleteModal">
                                <i class="far fa-trash-alt"></i>
                                {{ __('admin_users_table.shared.delete-selected') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    {{ $all_users->appends(['user_email_verified' => $user_email_verified, 'user_suspended' => $user_suspended, 'order_by' => $order_by])->links() }}
                </div>
            </div>

            <hr>

        </div>
    </div>

    <!-- Start forms for selected buttons -->
    <form action="{{ route('admin.users.bulk.verify', $request_query_array) }}" method="POST" id="form_verify_selected">
        @csrf
    </form>

    <form action="{{ route('admin.users.bulk.suspend', $request_query_array) }}" method="POST" id="form_suspend_selected">
        @csrf
    </form>

    <form action="{{ route('admin.users.bulk.unsuspend', $request_query_array) }}" method="POST" id="form_unsuspend_selected">
        @csrf
    </form>
    <!-- End forms for selected buttons -->

    <!-- Modal Delete User -->
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
                    {{ __('admin_users_table.delete-selected-users-confirm') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>

                    <form action="{{ route('admin.users.bulk.delete', $request_query_array) }}" method="POST" id="form_delete_selected">
                        @csrf
                        <button id="delete_selected_button"  class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
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

                $(".users_table_index_checkbox").each(function (index) {
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

                $(".users_table_index_checkbox").each(function (index) {
                    $(this).prop('checked', false);
                });

            });
            /**
             * End un-select all button
             */

            /**
             * Start verify selected button action
             */
            $('#verify_selected_button').on('click', function () {

                $(".users_table_index_checkbox:checked").each(function (index) {

                    var selected_checkbox_value = $(this).val();

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'user_id[]',
                        value: selected_checkbox_value
                    }).appendTo('#form_verify_selected');

                });

                $("#form_verify_selected").submit();
            });
            /**
             * End verify selected button action
             */


            /**
             * Start suspend selected button action
             */
            $('#suspend_selected_button').on('click', function () {

                $(".users_table_index_checkbox:checked").each(function (index) {

                    var selected_checkbox_value = $(this).val();

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'user_id[]',
                        value: selected_checkbox_value
                    }).appendTo('#form_suspend_selected');

                });

                $("#form_suspend_selected").submit();
            });
            /**
             * End suspend selected button action
             */


            /**
             * Start unlock selected button action
             */
            $('#un_lock_selected_button').on('click', function () {

                $(".users_table_index_checkbox:checked").each(function (index) {

                    var selected_checkbox_value = $(this).val();

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'user_id[]',
                        value: selected_checkbox_value
                    }).appendTo('#form_unsuspend_selected');

                });

                $("#form_unsuspend_selected").submit();
            });
            /**
             * End unlock selected button action
             */


            /**
             * Start delete selected button action
             */
            $('#delete_selected_button').on('click', function () {

                $(".users_table_index_checkbox:checked").each(function (index) {

                    var selected_checkbox_value = $(this).val();

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'user_id[]',
                        value: selected_checkbox_value
                    }).appendTo('#form_delete_selected');

                });

                $("#form_delete_selected").submit();
            });
            /**
             * End delete selected button action
             */
        });
    </script>
@endsection

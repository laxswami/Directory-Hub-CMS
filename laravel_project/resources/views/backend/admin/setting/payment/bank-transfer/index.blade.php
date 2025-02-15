@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('bank_transfer.bank-transfer-manage') }}</h1>
            <p class="mb-4">{{ __('bank_transfer.bank-transfer-manage-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.settings.payment.bank-transfer.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('bank_transfer.add-bank-transfer') }}</span>
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
                                <th>{{ __('bank_transfer.id') }}</th>
                                <th>{{ __('bank_transfer.bank-name') }}</th>
                                <th>{{ __('bank_transfer.bank-account-info') }}</th>
                                <th>{{ __('bank_transfer.status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('bank_transfer.id') }}</th>
                                <th>{{ __('bank_transfer.bank-name') }}</th>
                                <th>{{ __('bank_transfer.bank-account-info') }}</th>
                                <th>{{ __('bank_transfer.status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_bank_transfers as $key => $bank_transfer)
                                <tr>
                                    <td>{{ $bank_transfer->id }}</td>
                                    <td>{{ $bank_transfer->setting_bank_transfer_bank_name }}</td>
                                    <td>{{ $bank_transfer->setting_bank_transfer_bank_account_info }}</td>
                                    <td>
                                        @if($bank_transfer->setting_bank_transfer_status == \App\Setting::SITE_PAYMENT_BANK_TRANSFER_ENABLE)
                                            <a class="btn btn-success btn-sm text-white">{{ __('bank_transfer.enable') }}</a>
                                        @else
                                            <a class="btn btn-warning btn-sm text-white">{{ __('bank_transfer.disable') }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.settings.payment.bank-transfer.edit', ['setting_bank_transfer' => $bank_transfer->id]) }}" class="btn btn-primary btn-circle">
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

            <div class="row pt-5">
                <div class="col-12">
                    <a href="{{ route('admin.settings.payment.bank-transfer.pending.index') }}">
                        <i class="far fa-list-alt"></i>
                        {{ __('bank_transfer.view-all-pending-invoices') }}
                    </a>
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

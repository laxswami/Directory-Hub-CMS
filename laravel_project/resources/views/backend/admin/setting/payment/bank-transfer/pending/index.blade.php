@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('bank_transfer.pending-invoices') }}</h1>
            <p class="mb-4">{{ __('bank_transfer.pending-invoices-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.settings.payment.bank-transfer.index') }}" class="btn btn-info btn-icon-split">
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
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{ __('backend.subscription.invoice-num') }}</th>
                                <th>{{ __('backend.subscription.title') }}</th>
                                <th>{{ __('backend.subscription.description') }}</th>
                                <th>{{ __('backend.subscription.amount') }}</th>
                                <th>{{ __('backend.subscription.status') }}</th>
                                <th>{{ __('backend.subscription.date') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('backend.subscription.invoice-num') }}</th>
                                <th>{{ __('backend.subscription.title') }}</th>
                                <th>{{ __('backend.subscription.description') }}</th>
                                <th>{{ __('backend.subscription.amount') }}</th>
                                <th>{{ __('backend.subscription.status') }}</th>
                                <th>{{ __('backend.subscription.date') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($pending_invoices as $key => $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_num }}</td>
                                    <td>{{ $invoice->invoice_item_title }}</td>
                                    <td>{{ $invoice->invoice_item_description }}</td>
                                    <td>{{ $invoice->invoice_amount }}</td>
                                    <td>{{ $invoice->invoice_status }}</td>
                                    <td>{{ $invoice->created_at }}</td>
                                    <td>
                                        <a href="{{ route('admin.settings.payment.bank-transfer.pending.show', ['invoice' => $invoice->id]) }}" class="btn btn-primary btn-circle">
                                            <i class="fas fa-search"></i>
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

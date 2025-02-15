@extends('backend.user.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('item_claim.item-claim-user') }}</h1>
            <p class="mb-4">{{ __('item_claim.item-claim-user-desc') }}</p>
        </div>
        <div class="col-3 text-right">
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
                            <form class="form-inline" action="{{ route('user.item-claims.index') }}" method="GET">
                                <div class="form-group mr-2">
                                    <select class="custom-select" name="item_claim_status">
                                        <option value="0">{{ __('item_claim.show-all') }}</option>
                                        <option value="{{ \App\ItemClaim::ITEM_CLAIM_FILTER_REQUESTED }}" {{ $item_claim_status == \App\ItemClaim::ITEM_CLAIM_FILTER_REQUESTED ? 'selected' : '' }}>{{ __('item_claim.status-requested') }}</option>
                                        <option value="{{ \App\ItemClaim::ITEM_CLAIM_FILTER_DISAPPROVED }}" {{ $item_claim_status == \App\ItemClaim::ITEM_CLAIM_FILTER_DISAPPROVED ? 'selected' : '' }}>{{ __('item_claim.status-approved') }}</option>
                                        <option value="{{ \App\ItemClaim::ITEM_CLAIM_FILTER_APPROVED }}" {{ $item_claim_status == \App\ItemClaim::ITEM_CLAIM_FILTER_APPROVED ? 'selected' : '' }}>{{ __('item_claim.status-disapproved') }}</option>
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
                                <th>{{ __('item_claim.claim-id') }}</th>
                                <th>{{ __('item_claim.claim-item') }}</th>
                                <th>{{ __('item_claim.claim-full-name') }}</th>
                                <th>{{ __('item_claim.claim-status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('item_claim.claim-id') }}</th>
                                <th>{{ __('item_claim.claim-item') }}</th>
                                <th>{{ __('item_claim.claim-full-name') }}</th>
                                <th>{{ __('item_claim.claim-status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_item_claims as $key => $item_claim)
                                <tr>
                                    <td>{{ $item_claim->id }}</td>
                                    <td>{{ $item_claim->item->item_title }}</td>
                                    <td>{{ $item_claim->item_claim_full_name }}</td>
                                    <td>
                                        @if($item_claim->item_claim_status == \App\ItemClaim::ITEM_CLAIM_STATUS_REQUESTED)
                                            <span class="text-warning">{{ __('item_claim.status-requested') }}</span>
                                        @elseif($item_claim->item_claim_status == \App\ItemClaim::ITEM_CLAIM_STATUS_APPROVED)
                                            <span class="text-success">{{ __('item_claim.status-approved') }}</span>
                                        @elseif($item_claim->item_claim_status == \App\ItemClaim::ITEM_CLAIM_STATUS_DISAPPROVED)
                                            <span class="text-danger">{{ __('item_claim.status-disapproved') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('user.item-claims.edit', $item_claim->id) }}" class="btn btn-primary btn-circle">
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

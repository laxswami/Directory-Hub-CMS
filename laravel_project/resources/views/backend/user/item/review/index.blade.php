@extends('backend.user.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('review.backend.manage-reviews') }}</h1>
            <p class="mb-4">{{ __('review.backend.manage-reviews-desc-user') }}</p>
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
                            <form class="form-inline" action="{{ route('user.items.reviews.index') }}" method="GET">
                                <div class="form-group mr-2">
                                    <select class="custom-select" name="reviews_type">
                                        <option value="all" {{ ($reviews_type == 'all' || empty($reviews_type)) ? 'selected' : '' }}>{{ __('review.backend.all-reviews') }}</option>
                                        <option value="pending" {{ $reviews_type == 'pending' ? 'selected' : '' }}>{{ __('review.backend.review-pending') }}</option>
                                        <option value="approved" {{ $reviews_type == 'approved' ? 'selected' : '' }}>{{ __('review.backend.review-approved') }}</option>
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
                                <th>{{ __('review.backend.id') }}</th>
                                <th>{{ __('review.backend.overall-rating') }}</th>
                                <th>{{ __('review.backend.customer-service') }}</th>
                                <th>{{ __('review.backend.quality') }}</th>
                                <th>{{ __('review.backend.friendly') }}</th>
                                <th>{{ __('review.backend.pricing') }}</th>
                                <th>{{ __('review.backend.title') }}</th>
                                <th>{{ __('review.backend.description') }}</th>
                                <th>{{ __('review.backend.status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('review.backend.id') }}</th>
                                <th>{{ __('review.backend.overall-rating') }}</th>
                                <th>{{ __('review.backend.customer-service') }}</th>
                                <th>{{ __('review.backend.quality') }}</th>
                                <th>{{ __('review.backend.friendly') }}</th>
                                <th>{{ __('review.backend.pricing') }}</th>
                                <th>{{ __('review.backend.title') }}</th>
                                <th>{{ __('review.backend.description') }}</th>
                                <th>{{ __('review.backend.status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($reviews as $key => $review)
                                <tr>
                                    <td>{{ $review->id }}</td>
                                    <td>{{ $review->rating }}</td>
                                    <td>{{ $review->customer_service_rating }}</td>
                                    <td>{{ $review->quality_rating }}</td>
                                    <td>{{ $review->friendly_rating }}</td>
                                    <td>{{ $review->pricing_rating }}</td>
                                    <td>{{ $review->title }}</td>
                                    <td>{{ str_limit($review->body, 100) }}</td>
                                    <td>
                                        @if($review->approved == \App\Item::ITEM_REVIEW_APPROVED)

                                            <span class="text-success">{{ __('review.backend.review-approved') }}</span>
                                        @else

                                            <span class="text-warning">{{ __('review.backend.review-pending') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('user.items.reviews.edit', ['item_slug' => \App\Item::find($review->reviewrateable_id)->item_slug, 'review' => $review->id]) }}" class="btn btn-sm btn-primary mb-1">
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

        $(document).ready(function() {

            "use strict";

            $('#dataTable').DataTable();
        });
    </script>
@endsection

@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.testimonial.testimonial') }}</h1>
            <p class="mb-4">{{ __('backend.testimonial.testimonial-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.testimonial.add-testimonial') }}</span>
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
                                <th>{{ __('backend.testimonial.id') }}</th>
                                <th>{{ __('backend.testimonial.name') }}</th>
                                <th>{{ __('backend.testimonial.company') }}</th>
                                <th>{{ __('backend.testimonial.job-title') }}</th>
                                <th>{{ __('backend.testimonial.image') }}</th>
                                <th>{{ __('backend.testimonial.description') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('backend.testimonial.id') }}</th>
                                <th>{{ __('backend.testimonial.name') }}</th>
                                <th>{{ __('backend.testimonial.company') }}</th>
                                <th>{{ __('backend.testimonial.job-title') }}</th>
                                <th>{{ __('backend.testimonial.image') }}</th>
                                <th>{{ __('backend.testimonial.description') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_testimonials as $key => $testimonial)
                                <tr>
                                    <td>{{ $testimonial->id }}</td>
                                    <td>{{ $testimonial->testimonial_name }}</td>
                                    <td>{{ $testimonial->testimonial_company }}</td>
                                    <td>{{ $testimonial->testimonial_job_title }}</td>

                                    @if(empty($testimonial->testimonial_image))
                                        <td><img src="{{ asset('backend/images/placeholder/profile-' . intval($testimonial->id % 10) . '.webp') }}" class="img-responsive"></td>
                                    @else
                                        <td><img src="{{ Storage::disk('public')->url('testimonial/'. $testimonial->testimonial_image) }}" class="img-responsive"></td>
                                    @endif

                                    <td>{{ $testimonial->testimonial_description }}</td>
                                    <td>
                                        <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="btn btn-primary btn-circle">
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

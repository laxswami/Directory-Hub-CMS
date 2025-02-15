@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('trans.sync-title') }}</h1>
            <p class="mb-4">{{ __('trans.sync-desc') }}</p>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row mb-3">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.lang.sync.do') }}">
                        @csrf
                        <button type="submit" class="btn btn-success">{{ __('trans.sync-button') }}</button>
                    </form>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12">
                    <p><strong>{{ __('trans.sync-index-questions') }}</strong></p>
                    <p>{{ __('trans.sync-index-answer-1') }}</p>
                    <p>{{ __('trans.sync-index-answer-2') }}</p>
                    <p>{{ __('trans.sync-index-help') }}</p>
                    <p><strong>{{ __('trans.sync-index-max-exe-time') . " " . ini_get('max_execution_time') . " " . __('trans.sync-index-second')}}</strong></p>
                </div>
            </div>
            <hr>
            <div class="row mt-5">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.lang.sync.restore') }}">
                        @csrf
                        <button type="submit" class="btn btn-warning">{{ __('trans.sync-restore') }}</button>
                        <small id="latHelpBlock" class="form-text text-muted">
                            {{ __('trans.sync-restore-help') }}
                        </small>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
@endsection

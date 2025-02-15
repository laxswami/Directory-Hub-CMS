@extends('backend.user.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.user.account-suspended') }}</h1>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <p>{{ __('backend.user.account-suspended-desc') }}</p>
            <a href="{{ route("page.contact") }}" class="btn btn-success">{{ __('backend.user.contact-webmaster') }}</a>
        </div>
    </div>

@endsection

@section('scripts')
@endsection

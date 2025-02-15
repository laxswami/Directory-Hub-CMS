@extends('backend.user.layouts.app')

@section('styles')
@endsection

@section('content')

    @if($paid_subscription_days_left == 1)
        <div class="alert alert-warning" role="alert">
            {{ __('backend.subscription.subscription-end-soon-day') }}
        </div>
    @elseif($paid_subscription_days_left > 1 && $paid_subscription_days_left <= \App\Subscription::PAID_SUBSCRIPTION_LEFT_DAYS)
        <div class="alert alert-warning" role="alert">
            {{ __('backend.subscription.subscription-end-soon-days', ['days_left' => $paid_subscription_days_left]) }}
        </div>
    @endif

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('backend.homepage.dashboard') }}</h1>
        <a href="{{ route('user.items.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
            <span class="text">{{ __('backend.homepage.post-a-listing') }}</span>
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('backend.homepage.pending-listings') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pending_item_count) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('backend.homepage.all-listings') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($item_count) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('backend.homepage.all-messages') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($message_count) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('backend.homepage.all-comments') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($comment_count) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comment-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('backend.homepage.message') }}</h6>
                </div>
                <div class="card-body">

                    @foreach($recent_threads as $recent_threads_key => $thread)
                        <div class="row pt-2 pb-2 {{ $recent_threads_key%2 == 0 ? 'bg-light' : '' }}">
                            <div class="col-9">
                                <span>{{ $thread->latestMessage->body }}</span>
                            </div>
                            <div class="col-3 text-right">
                                <span>{{ $thread->latestMessage->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="row text-center mt-3">
                        <div class="col-12">
                            <a href="{{ route('user.messages.index') }}">{{ __('backend.homepage.view-all-message') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('backend.homepage.comment') }}</h6>
                </div>
                <div class="card-body">

                    @foreach($recent_comments as $recent_comments_key => $comment)
                        <div class="row pt-2 pb-2 {{ $recent_comments_key%2 == 0 ? 'bg-light' : '' }}">
                            <div class="col-9">
                                <span>{{ $comment->comment }}</span>
                            </div>
                            <div class="col-3 text-right">
                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="row text-center mt-3">
                        <div class="col-12">
                            <a href="{{ route('user.comments.index') }}">{{ __('backend.homepage.view-all-comment') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@endsection

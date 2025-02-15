@extends('backend.user.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.comment.comment') }}</h1>
            <p class="mb-4">{{ __('backend.comment.comment-desc-user') }}</p>
        </div>
        <div class="col-3 text-right">
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
                                <th>{{ __('backend.comment.id') }}</th>
                                <th>{{ __('backend.comment.type') }}</th>
                                <th>{{ __('backend.comment.comment') }}</th>
                                <th>{{ __('backend.comment.status') }}</th>
                                <th>{{ __('backend.comment.date') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('backend.comment.id') }}</th>
                                <th>{{ __('backend.comment.type') }}</th>
                                <th>{{ __('backend.comment.comment') }}</th>
                                <th>{{ __('backend.comment.status') }}</th>
                                <th>{{ __('backend.comment.date') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_comments as $key => $comment)
                                <tr>
                                    <td>{{ $comment->id }}</td>
                                    <td>
                                        @if($comment->commentable_type == 'App\Item')
                                            <a href="{{ route('page.item', \App\Item::find($comment->commentable_id)->item_slug) . '#comment-' . $comment->id }}" target="_blank" class="btn btn-primary btn-sm">{{ __('backend.sidebar.listing') }}</a>
                                        @else
                                            <a href="{{ route('page.blog.show', \Canvas\Post::find($comment->commentable_id)->slug) . '#comment-' . $comment->id }}" target="_blank" class="btn btn-info btn-sm">{{ __('backend.sidebar.blog') }}</a>
                                        @endif
                                    </td>
                                    <td>{{ $comment->comment }}</td>
                                    <td>
                                        @if($comment->approved)
                                            <a class="btn btn-success btn-sm text-white">{{ __('backend.shared.approved') }}</a>
                                        @else
                                            <a class="btn btn-secondary btn-sm text-white">{{ __('backend.shared.pending') }}</a>
                                        @endif
                                    </td>
                                    <td>{{ $comment->created_at->diffForHumans() }}</td>
                                    <td>
                                        @if($comment->commentable_type == 'App\Item')
                                            <a href="{{ route('page.item', \App\Item::find($comment->commentable_id)->item_slug) . '#comment-' . $comment->id }}" target="_blank" class="btn btn-info btn-sm">{{ __('backend.comment.detail') }}</a>
                                        @else
                                            <a href="{{ route('page.blog.show', \Canvas\Post::find($comment->commentable_id)->slug) . '#comment-' . $comment->id }}" target="_blank" class="btn btn-info btn-sm">{{ __('backend.comment.detail') }}</a>
                                        @endif
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

@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.comment.comment') }}</h1>
            <p class="mb-4">{{ __('backend.comment.comment-desc') }}</p>
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
                                <th>{{ __('backend.comment.name') }}</th>
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
                                <th>{{ __('backend.comment.name') }}</th>
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
                                    <td>{{ \App\User::find($comment->commenter_id)->name }}</td>
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
                                        @if($comment->approved)
                                            <form action="{{ route('admin.comments.disapprove', $comment) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-warning">{{ __('backend.shared.disapprove') }}</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success">{{ __('backend.shared.approve') }}</button>
                                            </form>
                                        @endif

                                            <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteModal{{ $comment->id }}">
                                                {{ __('backend.shared.delete') }}
                                            </a>
                                            <!-- Modal -->
                                            <div class="modal fade" id="deleteModal{{ $comment->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModal{{ $comment->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle{{ $comment->id }}">{{ __('backend.shared.delete-confirm') }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ __('backend.shared.delete-message', ['record_type' => __('backend.shared.comment'), 'record_name' => $comment->comment]) }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                                                            <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">{{ __('backend.shared.delete') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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

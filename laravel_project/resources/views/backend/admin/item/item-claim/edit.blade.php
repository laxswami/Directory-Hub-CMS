@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('item_claim.item-claim-edit-admin') }}</h1>
            <p class="mb-4">{{ __('item_claim.item-claim-edit-admin-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.item-claims.index') }}" class="btn btn-info btn-icon-split">
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
            <div class="row mb-5">
                <div class="col-3">
                    @if(empty($item->item_image))
                        <img id="image_preview" src="{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}" class="img-responsive rounded">
                    @else
                        <img id="image_preview" src="{{ Storage::disk('public')->url('item/'. $item->item_image) }}" class="img-responsive rounded">
                    @endif

                        <a target="_blank" href="{{ route('page.item', $item->item_slug) }}" class="btn btn-primary btn-block mt-2">{{ __('backend.message.view-listing') }}</a>

                </div>
                <div class="col-9">
                    <p>
                        @foreach($item->allCategories()->get() as $key => $category)
                            <span class="bg-info rounded text-white pl-2 pr-2 pt-1 pb-1 mr-1">
                                {{ $category->category_name }}
                            </span>
                        @endforeach
                    </p>
                    <h1 class="h4 mb-2 text-gray-800">{{ $item->item_title }}</h1>
                    @if($item_has_claimed)
                        <p>
                            <i class="fas fa-check-circle"></i>
                            {{ __('item_claim.item-claimed-by') . " " . $item_claimed_user->name }}
                        </p>
                    @else
                        <p>
                            <i class="fas fa-question-circle"></i>
                            {{ __('item_claim.unclaimed') . ", " . __('item_claim.item-posted-by') . " " . $item->user->name }}
                        </p>
                    @endif
                    <p>
                        @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                        {{ $item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE ? $item->item_address . ', ' : '' }} {{ $item->city->city_name . ', ' . $item->state->state_name . ' ' . $item->item_postal_code }}
                        @else
                            <span class="bg-primary text-white pl-1 pr-1 rounded">{{ __('theme_directory_hub.online-listing.online-listing') }}</span>
                        @endif
                    </p>
                    <hr/>
                    <p>{{ $item->item_description }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <span class="text-gray-800">{{ __('item_claim.claim-user') }}:</span>
                            @if($item_claim->user_id == \Illuminate\Support\Facades\Auth::user()->id)
                                <a href="{{ route('admin.users.profile.edit') }}">
                                    {{ $item_claim->user->name }}
                                </a>
                            @else
                                <a href="{{ route('admin.users.edit', ['user' => $item_claim->user_id]) }}">
                                    {{ $item_claim->user->name }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <span class="text-gray-800">{{ __('item_claim.claim-status') }}:</span>
                            @if($item_claim->item_claim_status == \App\ItemClaim::ITEM_CLAIM_STATUS_REQUESTED)
                                <span class="text-warning">{{ __('item_claim.status-requested') }}</span>
                            @elseif($item_claim->item_claim_status == \App\ItemClaim::ITEM_CLAIM_STATUS_DISAPPROVED)
                                <span class="text-danger">{{ __('item_claim.status-disapproved') }}</span>
                            @elseif($item_claim->item_claim_status == \App\ItemClaim::ITEM_CLAIM_STATUS_APPROVED)
                                <span class="text-success">{{ __('item_claim.status-approved') }}</span>
                            @endif
                        </div>
                    </div>

                    @if(!empty($item_claim->item_claim_reply))
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <span class="text-gray-800">{{ __('item_claim.admin-feedback') }}:</span>
                            <span>{{ $item_claim->item_claim_reply }}</span>
                        </div>
                    </div>
                    @endif

                    @if(!empty($item_claim->item_claim_additional_upload))
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <span class="text-gray-800">{{ __('item_claim.document-uploaded') }}:</span>
                                {{ $item_claim->item_claim_additional_upload }}
                                <button id="item_claim_additional_upload_download" class="btn btn-primary btn-sm">{{ __('item_claim.download') }}</button>
                                <form id="item_claim_additional_upload_download_form" action="{{ route('admin.item-claims.download.do', ['item_claim' => $item_claim]) }}" method="POST" target="_blank">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @endif

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <a class="btn btn-success btn-sm @if($item_has_claimed) disabled @endif" href="#" data-toggle="modal" data-target="#approveModal">
                                {{ __('item_claim.approve-claim') }}
                            </a>
                            <a class="btn btn-danger btn-sm" href="#" data-toggle="modal" data-target="#disapproveModal">
                                {{ __('item_claim.disapprove-claim') }}
                            </a>
                        </div>
                    </div>

                        <hr>

                    <form method="POST" action="{{ route('admin.item-claims.update', ['item_claim' => $item_claim]) }}" class="" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if(!$item_claim_belong_to_admin)
                            <div class="form-row mb-3">
                                <div class="col-md-12">
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        {{ __('item_claim.item-claim-edit-others-warning') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <label for="item_claim_full_name" class="text-black">{{ __('item_claim.claim-full-name') }}</label>
                                <input id="item_claim_full_name" type="text" class="form-control @error('item_claim_full_name') is-invalid @enderror" name="item_claim_full_name" value="{{ old('item_claim_full_name') ? old('item_claim_full_name') : $item_claim->item_claim_full_name }}">
                                @error('item_claim_full_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">

                            <div class="col-md-6">
                                <label for="item_claim_phone" class="text-black">{{ __('item_claim.claim-phone') }}</label>
                                <input id="item_claim_phone" type="text" class="form-control @error('item_claim_phone') is-invalid @enderror" name="item_claim_phone" value="{{ old('item_claim_phone') ? old('item_claim_phone') : $item_claim->item_claim_phone }}">
                                @error('item_claim_phone')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="item_claim_email" class="text-black">{{ __('item_claim.claim-email') }}</label>
                                <input id="item_claim_email" type="text" class="form-control @error('item_claim_email') is-invalid @enderror" name="item_claim_email" value="{{ old('item_claim_email') ? old('item_claim_email') : $item_claim->item_claim_email }}">
                                @error('item_claim_email')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <label class="text-black" for="item_claim_additional_proof">{{ __('item_claim.claim-additional-proof') }}</label>
                                <textarea rows="6" id="item_claim_additional_proof" type="text" class="form-control @error('item_claim_additional_proof') is-invalid @enderror" name="item_claim_additional_proof">{{ old('item_claim_additional_proof') ? old('item_claim_additional_proof') : $item_claim->item_claim_additional_proof }}</textarea>
                                <small class="text-muted">
                                    {{ __('item_claim.claim-additional-proof-help') }}
                                </small>
                                @error('item_claim_additional_proof')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <label class="text-black" for="item_claim_additional_upload">{{ __('item_claim.claim-additional-doc') }}</label>
                                <input id="item_claim_additional_upload" type="file" class="form-control @error('item_claim_additional_upload') is-invalid @enderror" name="item_claim_additional_upload">
                                <small class="form-text text-muted">
                                    {{ __('item_claim.claim-additional-doc-help') }}
                                </small>
                                @error('item_claim_additional_upload')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group justify-content-between">
                            <div class="col-8">
                                <button type="submit" class="btn btn-success text-white">
                                    {{ __('item_claim.update-claim-request') }}
                                </button>
                            </div>
                            <div class="col-4 text-right">
                                <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                                    {{ __('backend.shared.delete') }}
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($item_claim_belong_to_admin)
                        <p>{{ __('item_claim.delete-claim') }}</p>
                    @else
                        <p>{{ __('item_claim.delete-claim-others') }}</p>
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.item-claims.destroy', ['item_claim' => $item_claim]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    @if(!$item_has_claimed)
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('item_claim.approve-claim-modal-title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.item-claims.approve.do', ['item_claim' => $item_claim]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-row mb-3">
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                {{ __('item_claim.approve-claim-modal-help') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-row mb-3">
                        <div class="col-md-12">
                            <label class="text-black" for="item_claim_reply_approve">{{ __('item_claim.claim-reply-feedback') }}</label>
                            <textarea rows="6" id="item_claim_reply_approve" type="text" class="form-control @error('item_claim_reply_approve') is-invalid @enderror" name="item_claim_reply_approve">{{ old('item_claim_reply_approve') }}</textarea>
                            <small class="text-muted">
                                {{ __('item_claim.claim-reply-feedback-help') }}
                            </small>
                            @error('item_claim_reply_approve')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('item_claim.approve-claim') }}</button>

                </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Disapprove Modal -->
    <div class="modal fade" id="disapproveModal" tabindex="-1" role="dialog" aria-labelledby="disapproveModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('item_claim.disapprove-claim-modal-title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.item-claims.disapprove.do', ['item_claim' => $item_claim]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    {{ __('item_claim.disapprove-claim-modal-help') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <label class="text-black" for="item_claim_reply_disapprove">{{ __('item_claim.claim-reply-feedback') }}</label>
                                <textarea rows="6" id="item_claim_reply_disapprove" type="text" class="form-control @error('item_claim_reply_disapprove') is-invalid @enderror" name="item_claim_reply_disapprove">{{ old('item_claim_reply_disapprove') }}</textarea>
                                <small class="text-muted">
                                    {{ __('item_claim.claim-reply-feedback-help') }}
                                </small>
                                @error('item_claim_reply_disapprove')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('item_claim.disapprove-claim') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            "use strict";

            $('#item_claim_additional_upload_download').on('click', function(){
                $('#item_claim_additional_upload_download_form').submit();
                $("form :submit").attr("disabled", false);
            });
        });
    </script>
@endsection

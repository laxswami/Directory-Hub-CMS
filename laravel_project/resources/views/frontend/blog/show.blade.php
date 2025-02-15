@extends('frontend.layouts.app')

@section('styles')
@endsection

@section('content')

    @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_DEFAULT)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( {{ asset('frontend/images/placeholder/header-inner.webp') }});">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: {{ $site_innerpage_header_background_color }};">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( {{ Storage::disk('public')->url('customization/' . $site_innerpage_header_background_image) }});">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: #333333;">
    @endif

        @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            <div data-youtube="{{ $site_innerpage_header_background_youtube_video }}"></div>
        @endif

        <div class="container">
            <div class="row align-items-center justify-content-center text-center">

                <div class="col-md-10" data-aos="fade-up" data-aos-delay="400">


                    <div class="row justify-content-center mt-5">
                        <div class="col-md-8 text-center">
                            <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ $data['post']->title }}</h1>
                            @if($data['post']->topic()->count() != 0)
                                <p class="mb-0" style="color: {{ $site_innerpage_header_paragraph_font_color }};">{{ $data['post']->topic()->first()->name }}</p>
                            @else
                                <p class="mb-0" style="color: {{ $site_innerpage_header_paragraph_font_color }};">{{ __('frontend.blog.uncategorized') }}</p>
                            @endif
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">

            @if($ads_before_breadcrumb->count() > 0)
                @foreach($ads_before_breadcrumb as $ads_before_breadcrumb_key => $ad_before_breadcrumb)
                    <div class="row mb-5">
                        @if($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                            <div class="col-12 text-left">
                                <div>
                                    {!! $ad_before_breadcrumb->advertisement_code !!}
                                </div>
                            </div>
                        @elseif($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                            <div class="col-12 text-center">
                                <div>
                                    {!! $ad_before_breadcrumb->advertisement_code !!}
                                </div>
                            </div>
                        @elseif($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                            <div class="col-12 text-right">
                                <div>
                                    {!! $ad_before_breadcrumb->advertisement_code !!}
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach
            @endif

            <div class="row mb-4">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('page.home') }}">
                                    <i class="fas fa-bars"></i>
                                    {{ __('frontend.shared.home') }}
                                </a>
                            </li>

                            <li class="breadcrumb-item"><a href="{{ route('page.blog') }}">{{ __('frontend.blog.title') }}</a></li>
                            @if($data['post']->topic()->count() != 0)
                                <li class="breadcrumb-item"><a href="{{ route('page.blog.topic', $data['post']->topic()->first()->slug) }}">{{ $data['post']->topic()->first()->name }}</a></li>
                            @endif
                            <li class="breadcrumb-item active" aria-current="page">{{ $data['post']->title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if($ads_after_breadcrumb->count() > 0)
                @foreach($ads_after_breadcrumb as $ads_after_breadcrumb_key => $ad_after_breadcrumb)
                    <div class="row mb-5">
                        @if($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                            <div class="col-12 text-left">
                                <div>
                                    {!! $ad_after_breadcrumb->advertisement_code !!}
                                </div>
                            </div>
                        @elseif($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                            <div class="col-12 text-center">
                                <div>
                                    {!! $ad_after_breadcrumb->advertisement_code !!}
                                </div>
                            </div>
                        @elseif($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                            <div class="col-12 text-right">
                                <div>
                                    {!! $ad_after_breadcrumb->advertisement_code !!}
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach
            @endif

            <div class="row">

                <div class="col-md-8">

                    @if($ads_before_feature_image->count() > 0)
                        @foreach($ads_before_feature_image as $ads_before_feature_image_key => $ad_before_feature_image)
                            <div class="row mb-5">
                                @if($ad_before_feature_image->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_feature_image->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_feature_image->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_feature_image->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_feature_image->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_feature_image->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    @if(empty($data['post']->featured_image))
                        <div class="mb-3" style="min-height:627px;border-radius: 0.25rem;background-image:url({{ asset('frontend/images/placeholder/full_item_feature_image.webp') }});background-size:cover;background-repeat:no-repeat;background-position: center center;"></div>
                    @else
                        <div class="mb-3" style="min-height:627px;border-radius: 0.25rem;background-image:url({{ url('laravel_project/public' . $data['post']->featured_image) }});background-size:cover;background-repeat:no-repeat;background-position: center center;"></div>
                    @endif

                        <hr/>

                        @if($ads_before_title->count() > 0)
                            @foreach($ads_before_title as $ads_before_title_key => $ad_before_title)
                                <div class="row mb-5">
                                    @if($ad_before_title->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                        <div class="col-12 text-left">
                                            <div>
                                                {!! $ad_before_title->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_title->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                        <div class="col-12 text-center">
                                            <div>
                                                {!! $ad_before_title->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_title->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                        <div class="col-12 text-right">
                                            <div>
                                                {!! $ad_before_title->advertisement_code !!}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        @endif

                        <h2 class="font-size-regular text-black">{{ $data['post']->title }}</h2>
                        <div class="mb-5">
                            {{ __('frontend.blog.by') }} {{ $data['post']->user()->first()->name }}<span class="mx-1">&bullet;</span>
                            {{ $data['post']->updated_at->diffForHumans() }} <span class="mx-1">&bullet;</span>
                            @if($data['post']->topic()->count() != 0)
                                <a href="{{ route('page.blog.topic', $data['post']->topic()->first()->slug) }}">{{ $data['post']->topic()->first()->name }}</a>
                            @else
                                {{ __('frontend.blog.uncategorized') }}
                            @endif

                        </div>

                        @if($ads_before_post_content->count() > 0)
                            @foreach($ads_before_post_content as $ads_before_post_content_key => $ad_before_post_content)
                                <div class="row mb-5">
                                    @if($ad_before_post_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                        <div class="col-12 text-left">
                                            <div>
                                                {!! $ad_before_post_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_post_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                        <div class="col-12 text-center">
                                            <div>
                                                {!! $ad_before_post_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_post_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                        <div class="col-12 text-right">
                                            <div>
                                                {!! $ad_before_post_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        @endif

                        <div class="row post-body mb-3">
                            <div class="col-12">
                                {!! $data['post']->body !!}
                            </div>
                        </div>

                        @if($ads_after_post_content->count() > 0)
                            @foreach($ads_after_post_content as $ads_after_post_content_key => $ad_after_post_content)
                                <div class="row mb-5">
                                    @if($ad_after_post_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                        <div class="col-12 text-left">
                                            <div>
                                                {!! $ad_after_post_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_after_post_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                        <div class="col-12 text-center">
                                            <div>
                                                {!! $ad_after_post_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_after_post_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                        <div class="col-12 text-right">
                                            <div>
                                                {!! $ad_after_post_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        @endif

                        @if($data['post']->tags()->count() > 0)
                            <div class="row mb-3">
                                <div class="col-1">
                                    <h3 class="h5 text-black">{{ trans_choice('frontend.blog.tag', 1) }}</h3>
                                </div>
                                <div class="col-11">
                                    @foreach($data['post']->tags()->get() as $key => $tag)
                                        <a class="mr-2 mb-2 float-left bg-info text-white pl-2 pr-2 pt-1 pb-1" href="{{ route('page.blog.tag', $tag->slug) }}">{{ $tag->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($ads_before_comments->count() > 0)
                            @foreach($ads_before_comments as $ads_before_comments_key => $ad_before_comments)
                                <div class="row mb-5">
                                    @if($ad_before_comments->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                        <div class="col-12 text-left">
                                            <div>
                                                {!! $ad_before_comments->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_comments->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                        <div class="col-12 text-center">
                                            <div>
                                                {!! $ad_before_comments->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_comments->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                        <div class="col-12 text-right">
                                            <div>
                                                {!! $ad_before_comments->advertisement_code !!}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        @endif

                        <div class="row mb-3">
                            <div class="col-12">
                                <h3 class="h5 text-black mb-3">{{ trans_choice('frontend.blog.comment', 1) }}</h3>
                                @comments([
                                    'model' => $blog_post,
                                    'approved' => true,
                                    'perPage' => 10
                                ])
                            </div>
                        </div>

                        @if($ads_before_share->count() > 0)
                            @foreach($ads_before_share as $ads_before_share_key => $ad_before_share)
                                <div class="row mb-5">
                                    @if($ad_before_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                        <div class="col-12 text-left">
                                            <div>
                                                {!! $ad_before_share->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                        <div class="col-12 text-center">
                                            <div>
                                                {!! $ad_before_share->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                        <div class="col-12 text-right">
                                            <div>
                                                {!! $ad_before_share->advertisement_code !!}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        @endif

                        <h4 class="h5 mb-4 mt-4 text-black">{{ __('frontend.item.share') }}</h4>
                        <div class="row">
                            <div class="col-12">

                                <!-- Create link with share to Facebook -->
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-facebook" href="" data-social="facebook">
                                    <i class="fab fa-facebook-f"></i>
                                    {{ __('social_share.facebook') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-twitter" href="" data-social="twitter">
                                    <i class="fab fa-twitter"></i>
                                    {{ __('social_share.twitter') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-linkedin" href="" data-social="linkedin">
                                    <i class="fab fa-linkedin-in"></i>
                                    {{ __('social_share.linkedin') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-blogger" href="" data-social="blogger">
                                    <i class="fab fa-blogger-b"></i>
                                    {{ __('social_share.blogger') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-pinterest" href="" data-social="pinterest">
                                    <i class="fab fa-pinterest-p"></i>
                                    {{ __('social_share.pinterest') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-evernote" href="" data-social="evernote">
                                    <i class="fab fa-evernote"></i>
                                    {{ __('social_share.evernote') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-reddit" href="" data-social="reddit">
                                    <i class="fab fa-reddit-alien"></i>
                                    {{ __('social_share.reddit') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-buffer" href="" data-social="buffer">
                                    <i class="fab fa-buffer"></i>
                                    {{ __('social_share.buffer') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wordpress" href="" data-social="wordpress">
                                    <i class="fab fa-wordpress-simple"></i>
                                    {{ __('social_share.wordpress') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-weibo" href="" data-social="weibo">
                                    <i class="fab fa-weibo"></i>
                                    {{ __('social_share.weibo') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-skype" href="" data-social="skype">
                                    <i class="fab fa-skype"></i>
                                    {{ __('social_share.skype') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-telegram" href="" data-social="telegram">
                                    <i class="fab fa-telegram-plane"></i>
                                    {{ __('social_share.telegram') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-viber" href="" data-social="viber">
                                    <i class="fab fa-viber"></i>
                                    {{ __('social_share.viber') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-whatsapp" href="" data-social="whatsapp">
                                    <i class="fab fa-whatsapp"></i>
                                    {{ __('social_share.whatsapp') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wechat" href="" data-social="wechat">
                                    <i class="fab fa-weixin"></i>
                                    {{ __('social_share.wechat') }}
                                </a>
                                <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-line" href="" data-social="line">
                                    <i class="fab fa-line"></i>
                                    {{ __('social_share.line') }}
                                </a>

                            </div>
                        </div>

                        @if($ads_after_share->count() > 0)
                            @foreach($ads_after_share as $ads_after_share_key => $ad_after_share)
                                <div class="row mt-5">
                                    @if($ad_after_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                        <div class="col-12 text-left">
                                            <div>
                                                {!! $ad_after_share->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_after_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                        <div class="col-12 text-center">
                                            <div>
                                                {!! $ad_after_share->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_after_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                        <div class="col-12 text-right">
                                            <div>
                                                {!! $ad_after_share->advertisement_code !!}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        @endif

                </div>

                <div class="col-md-3 ml-auto">

                    @if($ads_before_sidebar_content->count() > 0)
                        @foreach($ads_before_sidebar_content as $ads_before_sidebar_content_key => $ad_before_sidebar_content)
                            <div class="row mb-5">
                                @if($ad_before_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_sidebar_content->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_sidebar_content->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_sidebar_content->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    @include('frontend.blog.partials.sidebar')

                    @if($ads_after_sidebar_content->count() > 0)
                        @foreach($ads_after_sidebar_content as $ads_after_sidebar_content_key => $ad_after_sidebar_content)
                            <div class="row mt-5">
                                @if($ad_after_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_after_sidebar_content->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_after_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_after_sidebar_content->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_after_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_after_sidebar_content->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif

                </div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('frontend/vendor/goodshare/goodshare.min.js') }}"></script>

    @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
    <!-- Youtube Background for Header -->
        <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif

    <script>
        $(document).ready(function(){

            "use strict";

            @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            /**
             * Start Initial Youtube Background
             */
            $("[data-youtube]").youtube_background();
            /**
             * End Initial Youtube Background
             */
            @endif

        });
    </script>
@endsection


@if($recent_posts->count() > 0)
    <div class="mb-5">
        <h3 class="h5 text-black mb-3">{{ __('frontend.blog.popular-posts') }}</h3>
        <ul class="list-unstyled">
            @foreach($recent_posts as $key => $post)
                <li class="mb-2"><a href="{{ route('page.blog.show', $post->slug) }}">{{ $post->title }}</a></li>
            @endforeach
        </ul>
    </div>
@endif


@if($all_topics->count() > 0)
    <div class="mb-5">
        <h3 class="h5 text-black mb-3">{{ trans_choice('frontend.blog.topic', 1) }}</h3>
        <ul class="list-unstyled">
            @foreach($all_topics as $key => $topic)
                <li class="mb-2"><a href="{{ route('page.blog.topic', $topic->slug) }}">{{ $topic->name }}</a></li>
            @endforeach
        </ul>
    </div>
@endif

@if($all_tags->count() > 0)
    <div class="mb-5">
        <h3 class="h5 text-black mb-3">{{ trans_choice('frontend.blog.tag', 1) }}</h3>
        <ul class="list-unstyled">
            @foreach($all_tags as $key => $tag)
                <a class="mr-2 mb-2 float-left bg-info text-white pl-2 pr-2 pt-1 pb-1" href="{{ route('page.blog.tag', $tag->slug) }}">{{ $tag->name }}</a>
            @endforeach
        </ul>
    </div>
    <div class="mb-5 div-clear"></div>
@endif

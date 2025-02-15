@component('mail::message')

# {{ __('canvas::app.your_weekly_writer_summary_for') }} {{ $data['endDate'] }}

{{ __('canvas::app.from') }} {{ $data['startDate'] }} {{ __('canvas::app.to') }} {{ $data['endDate'] }} {{ __('canvas::app.your_posts_received') }}

# {{ __('canvas::app.views') }}
## +{{ $data['totals']['views']  }}

# {{ __('canvas::app.visits') }}
## +{{ $data['totals']['visits']  }}

@component('mail::table')
|                                                                   | {{ __('canvas::app.visits') }}           | {{ __('canvas::app.views') }}             |
| ----------------------------------------------------------------- | ---------------------------------------: | -----------------------------------------:|
@foreach($data['posts'] as $post)
| *{{ \Illuminate\Support\Str::limit($post['title'], 40, '...') }}* | **+{{ number_format($post['visits']) }}** | **+{{ number_format($post['views']) }}** |
@endforeach
@endcomponent

@component('mail::button', ['url' => url(config('canvas.path'))])
{{ __('canvas::app.see_all_stats') }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

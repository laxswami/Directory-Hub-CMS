@if($site_global_settings->setting_site_google_analytic_enabled == \App\Setting::TRACKING_ON)

    @guest

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $site_global_settings->setting_site_google_analytic_tracking_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '{{ $site_global_settings->setting_site_google_analytic_tracking_id }}');
        </script>

    @else

        @if(!Auth::user()->isAdmin())

            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $site_global_settings->setting_site_google_analytic_tracking_id }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '{{ $site_global_settings->setting_site_google_analytic_tracking_id }}');
            </script>

        @else
            @if($site_global_settings->setting_site_google_analytic_not_track_admin == \App\Setting::TRACKING_ADMIN)

                <!-- Global site tag (gtag.js) - Google Analytics -->
                <script async src="https://www.googletagmanager.com/gtag/js?id={{ $site_global_settings->setting_site_google_analytic_tracking_id }}"></script>
                <script>
                    window.dataLayer = window.dataLayer || [];
                    function gtag(){dataLayer.push(arguments);}
                    gtag('js', new Date());

                    gtag('config', '{{ $site_global_settings->setting_site_google_analytic_tracking_id }}');
                </script>

            @endif
        @endif

    @endguest

@endif


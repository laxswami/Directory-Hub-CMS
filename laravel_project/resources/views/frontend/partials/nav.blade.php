<div class="site-mobile-menu">
    <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
        </div>
    </div>
    <div class="site-mobile-menu-body"></div>
</div>

<header class="site-navbar container py-0 bg-white customization-header-background-color" role="banner">

    <!-- <div class="container"> -->
    <div class="row align-items-center">

        <div class="col-8 col-xl-2 pr-0">

                @if(empty($site_global_settings->setting_site_logo))
                <h1 class="mb-0 site-logo">
                    <a href="{{ route('page.home') }}" class="text-black mb-0 customization-header-font-color">
                        @foreach(explode(' ', empty($site_global_settings->setting_site_name) ? config('app.name', 'Laravel') : $site_global_settings->setting_site_name) as $key => $word)
                            @if($key/2 == 0)
                                {{ $word }}
                            @else
                                <span class="text-primary">{{ $word }}</span>
                            @endif
                        @endforeach
                    </a>
                </h1>
                @else
                <h1 class="mb-0 mt-1 site-logo">
                    <a href="{{ route('page.home') }}" class="text-black mb-0">
                        <img src="{{ Storage::disk('public')->url('setting/' . $site_global_settings->setting_site_logo) }}">
                    </a>
                </h1>
                @endif


        </div>
        <div class="col-12 col-md-10 d-none d-xl-block">
            <nav class="site-navigation position-relative text-right" role="navigation">

                <ul class="site-menu js-clone-nav mr-auto d-none d-lg-block pl-4">
                    <li><a href="{{ route('page.home') }}">{{ __('frontend.header.home') }}</a></li>
                    <li><a href="{{ route('page.categories') }}">{{ __('frontend.header.listings') }}</a></li>
                    @if($site_global_settings->setting_page_about_enable == \App\Setting::ABOUT_PAGE_ENABLED)
                    <li><a href="{{ route('page.about') }}">{{ __('frontend.header.about') }}</a></li>
                    @endif
                    <li><a href="{{ route('page.blog') }}">{{ __('frontend.header.blog') }}</a></li>
                    <li><a href="{{ route('page.contact') }}">{{ __('frontend.header.contact') }}</a></li>

                    @guest
                        <li class="ml-xl-3 login"><a href="{{ route('login') }}"><span class="border-left pl-xl-4"></span>{{ __('frontend.header.login') }}</a></li>
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}">{{ __('frontend.header.register') }}</a></li>
                        @endif
                    @else
                        <li class="has-children">
                            <a href="#">{{ Auth::user()->name }}</a>
                            <ul class="dropdown">
                                <li>
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.index') }}">{{ __('frontend.header.dashboard') }}</a>
                                    @else
                                        <a href="{{ route('user.index') }}">{{ __('frontend.header.dashboard') }}</a>
                                    @endif
                                </li>
                                <li><a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('auth.logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                    <li>
                        @guest
                            <a href="{{ route('page.pricing') }}" class="cta"><span class="bg-primary text-white rounded"><i class="fas fa-plus mr-1"></i> {{ __('frontend.header.list-business') }}</span></a>
                        @else
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.items.create') }}" class="cta"><span class="bg-primary text-white rounded"><i class="fas fa-plus mr-1"></i> {{ __('frontend.header.list-business') }}</span></a>
                            @else
                                @if(Auth::user()->hasPaidSubscription())
                                    <a href="{{ route('user.items.create') }}" class="cta"><span class="bg-primary text-white rounded"><i class="fas fa-plus mr-1"></i> {{ __('frontend.header.list-business') }}</span></a>
                                @else
                                    <a href="{{ route('page.pricing') }}" class="cta"><span class="bg-primary text-white rounded"><i class="fas fa-plus mr-1"></i> {{ __('frontend.header.list-business') }}</span></a>
                                @endif
                            @endif
                        @endguest
                    </li>
                </ul>
            </nav>
        </div>


        <div class="d-inline-block d-xl-none ml-auto py-3 col-4 text-right" id="menu-mobile-div">
            <a href="#" class="site-menu-toggle js-menu-toggle text-black"><span class="icon-menu h3"></span></a>
        </div>

    </div>
    <!-- </div> -->

</header>

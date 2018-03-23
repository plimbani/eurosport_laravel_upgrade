<!doctype html>
<!--[if lte IE 9]><html lang="{{ app()->getLocale() }}" class="lt-ie10 lt-ie10-msg no-focus"> <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="{{ app()->getLocale() }}" class="no-focus">
<!--<![endif]-->

<head>
    @include('partials.frontend.google-analytics')

    <!-- Meta -->
    @include('partials.frontend.meta', ['pageTitle' => 'Error 404 - Page not found'])
    <!-- END Meta -->

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    @include('partials.frontend.favicons')
    <!-- END Icons -->

    <!-- Stylesheets required for application -->
    @include('partials.frontend.app-css')
    {{-- Stylesheets --}}

    {{-- Page specific plugin styles --}}
    @yield('plugin-styles')

    <link rel="stylesheet" href="{{ $theme_css }}">

    @yield('page-styles')
    {{-- END Stylesheets --}}
</head>

<body>
    @include('partials.frontend.header')

    <!-- Hero wrapper -->
    <div class="hero__wrapper hero__wrapper-small">
        @include('partials.frontend.header-menu')
        <div class="hero__wrapper-banner">
            <img src="{{ $hero_image }}" alt="">
        </div>
        <div class="hero__wrapper-overlay">
            <div class="container">
                <div class="row flex-column flex-lg-row align-items-center margin-area">
                    <div class="col-lg-3">
                        <div class="d-flex justify-content-center justify-content-lg-end">
                            <div class="club_logo">
                                <div class="club_logo-box">
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <img src="{{ $websiteDetail->tournamentLogo('small_thumbnail') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid-full club_info">
                        <h1 class="club_info-title">Error 404 - Page not found</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @yield('quick-links')
    <!-- End of hero wrapper -->

    <!-- Content wrapper -->
    <div class="content__wrapper">
        <div class="container">
            <div class="row my-5">
                <div class="col-lg-3">
                </div>
                <div class="col-lg-8 club_content">
                    {!! __('messages.404_page_message', ['url' => route('home.page.details', ['domain' => $websiteDetail->domain_name])]) !!}
                </div>
            </div>
        </div>
    </div>
    <!-- End of content wrapper -->

    @include('partials.frontend.sponsors')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <hr class="hr my-5">
            </div>
        </div>
    </div>

    @include('partials.frontend.footer')

    @yield('modals')

    <!-- Javscript required for application -->
    @include('partials.frontend.app-js')

    {{-- Plugin JS --}}
    @yield('plugin-scripts')

    {{-- Page specific custom scripts --}}
    @yield('page-scripts')

</body>
</html>

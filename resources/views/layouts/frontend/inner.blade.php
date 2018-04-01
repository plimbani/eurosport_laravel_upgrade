<!doctype html>
<!--[if lte IE 9]><html lang="{{ app()->getLocale() }}" class="lt-ie10 lt-ie10-msg no-focus"> <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="{{ app()->getLocale() }}" class="no-focus">
<!--<![endif]-->

<head>
    @if($websiteDetail->google_analytics_id)
        @include('partials.frontend.google-analytics')
    @endif

    <!-- Meta -->
    @include('partials.frontend.meta')
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

    @include('partials.frontend.inner-hero-section')

    @include('partials.frontend.tournament-messages')

    @include('flash::message')

    @yield('content')

    @include('partials.frontend.sponsors')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <hr class="hr">
            </div>
        </div>
    </div>

    @include('partials.frontend.footer')

    @yield('modals')

    <!-- Javscript required for application -->
    @include('partials.frontend.app-js')

    <script src="{{ asset('frontend/js/global.js') }}"></script>

    {{-- Plugin JS --}}
    @yield('plugin-scripts')

    {{-- Page specific custom scripts --}}
    @yield('page-scripts')

</body>
</html>

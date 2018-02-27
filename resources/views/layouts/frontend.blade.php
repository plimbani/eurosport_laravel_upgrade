<!doctype html>
<!--[if lte IE 9]><html lang="{{ app()->getLocale() }}" class="lt-ie10 lt-ie10-msg no-focus"> <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="{{ app()->getLocale() }}" class="no-focus">
<!--<![endif]-->

<head>
    @include('partials.frontend.google-analytics')

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

    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/frontend.css') }}">

    @yield('page-styles')
    {{-- END Stylesheets --}}
</head>

<body>
    <div>
        @include('partials.frontend.header')
        <main id="main-container">
            @include('partials.frontend.messages')
            @include('flash::message')
            @yield('content')
        </main>
        @include('partials.frontend.footer')
    </div>

    @yield('modals')

    <!-- Stylesheets required for application -->
    @include('partials.frontend.app-js')

    <script src="{{ asset('assets/js/frontend.js') }}"></script>

    {{-- Plugin JS --}}
    @yield('plugin-scripts')

    {{-- Page specific custom scripts --}}
    @yield('page-scripts')

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('wot.google_api_key') }}"></script>
</body>
</html>

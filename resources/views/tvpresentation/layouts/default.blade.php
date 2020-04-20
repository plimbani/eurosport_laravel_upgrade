<!doctype html>
<html lang="{{ config('app.locale') }}" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Presentation mode - @yield('title')</title>

        @include('tvpresentation/partials/meta/meta')

        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Icons --}}
        @include('tvpresentation/partials/favicons/favicons')

        {{-- Fonts and Styles --}}
        @yield('css_before')
        @include('tvpresentation/partials/styles')

        @yield('css_after')

        {{-- Scripts --}}
        <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
    </head>
    <body class="body-container @yield('body_class')">
        <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

        {{-- Page Container --}}

        <div class="content">
            <div class="data-container">
                <div class="sidebar">
                    @include('tvpresentation/partials/sidebar')
                </div>
                <div class="main-body">
                    {{-- Main Container --}}
                        @yield('content')
                    {{-- END Main Container --}}
                </div>
            </div>
        </div>

        {{-- END Page Container --}}

        @include('tvpresentation/partials/scripts')
        @yield('js_after')
    </body>
</html>

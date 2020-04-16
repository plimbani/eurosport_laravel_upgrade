<!doctype html>
<html lang="{{ config('app.locale') }}" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Presentation mode - @yield('title')</title>

        @include('tv-presets/partials/meta/meta')

        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Icons --}}
        @include('tv-presets/partials/favicons/favicons')

        {{-- Fonts and Styles --}}
        @yield('css_before')
        @include('tv-presets/partials/styles')

        @yield('css_after')

        {{-- Scripts --}}
        <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
    </head>
    <body class="body-container @yield('body_class')">
        <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->
        {{-- Page Container --}}
        <div class="selection-content">
            <div class="container">
                {{-- Main Container --}}
                    @yield('content')
                {{-- END Main Container --}}
            </div>
        </div>
        <div class="selection-footer">
            <div class="container">
                <div class="footer-text is-center">Copyright 2020 TMP Applications BV. Developed by aecor.</div>
            </div>
        </div>
        {{-- END Page Container --}}

        @include('tv-presets/partials/scripts')
        @yield('js_after')
    </body>
</html>

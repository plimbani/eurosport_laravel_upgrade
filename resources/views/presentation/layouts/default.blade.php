<!doctype html>
<html lang="{{ config('app.locale') }}" class="no-focus">
    <head>
        @include('presentation/partials/header')
    </head>
    <body class="body-container @yield('body_class')">
        <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

        @yield('content')

        @include('presentation/partials/scripts')
        <script type="text/javascript" src="{{mix('/assets/js/core/plugins.js')}}"></script>
        <script type="text/javascript" src="{{mix('/assets/js/app.js')}}"></script>
        @yield('js_after')
    </body>
</html>

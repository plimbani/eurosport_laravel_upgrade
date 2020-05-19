<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

<title>Presentation mode - @yield('title')</title>

@include('presentation/partials/meta/meta')

{{-- CSRF Token --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Icons --}}
@include('presentation/partials/favicons/favicons')

{{-- Fonts and Styles --}}
@yield('css_before')
@include('presentation/partials/styles')

@yield('css_after')

{{-- Scripts --}}
<script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
@extends('layouts.frontend.inner')

@section('content')

<h1>{!! __('messages.accommodation') !!}</h1>

{!! $accommodationContent->content !!}

@endsection
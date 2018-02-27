@extends('layouts.frontend')

@section('content')

<h1>{!! __('messages.stay') !!}</h1>

{!! $additionalPage->content !!}

@endsection
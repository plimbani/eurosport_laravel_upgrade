@extends('layouts.frontend')

@section('content')

<h1>{!! __('messages.rules') !!}</h1>

{!! $rulesContent->content !!}

@endsection
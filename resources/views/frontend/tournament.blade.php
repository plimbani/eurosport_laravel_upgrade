@extends('layouts.frontend')

@section('content')

<h1>{!! __('messages.tourist_information') !!}</h1>

{!! $tournamentContent->content !!}

@endsection
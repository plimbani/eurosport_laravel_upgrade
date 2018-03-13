@extends('layouts.frontend')

@section('content')

<h1>{!! __('messages.tournament') !!}</h1>

{!! $tournamentContent->content !!}

@endsection
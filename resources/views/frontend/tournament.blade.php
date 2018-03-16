@extends('layouts.frontend.inner')

@section('content')

<h1>{!! __('messages.tournament') !!}</h1>

{!! $tournamentContent->content !!}

@endsection
@extends('layouts.frontend')

@section('content')

  <div id="tournament_history">
    <h1>{!! __('messages.history') !!}</h1>

    <tournament-history></tournament-history>
  </div>

@endsection

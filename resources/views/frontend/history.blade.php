@extends('layouts.frontend')

@section('content')

  <div id="tournament_history">
    <h1>{!! __('messages.history') !!}</h1>

    <tournament-history></tournament-history>
  </div>

@endsection

@section('page-scripts')
  <script type="text/javascript">
    var tournamentData = {!! json_encode($tournament) !!};
  </script>
  <script type="text/javascript" src="{{ mix('assets/js/frontend/tournamenthistory.js') }}"></script>
@endsection

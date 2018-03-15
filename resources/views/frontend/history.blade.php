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
    var competitionList = {!! json_encode($competitionList) !!};
    var allHistoryYears = {!! json_encode($allHistoryYears) !!}
  </script>
  <script type="text/javascript" src="{{ mix('assets/js/frontend/tournamenthistory.js') }}"></script>
@endsection

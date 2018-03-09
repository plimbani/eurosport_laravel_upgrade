@extends('layouts.frontend')

@section('content')
  <div id="matches_list">
    <h2 class="text-center">{!! __('messages.matches') !!}</h2>
    @if($tournament)
      <match-listing></match-listing>
    @else
      {!! __('messages.match_schedule_message') !!}
    @endif
  </div>
@endsection

@section('page-scripts')
  <script type="text/javascript">
    var tournamentData = {!! json_encode($tournament) !!};
  </script>
  <script type="text/javascript" src="{{ mix('assets/js/core/matches-plugins.js') }}"></script>
  <script type="text/javascript" src="{{ mix('assets/js/frontend/matchlist.js') }}"></script>
@endsection

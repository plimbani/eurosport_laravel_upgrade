@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="col-lg-8 club_info">
		<span class="club_info-detail">{!! __('messages.tournament') !!}</span>
		<h1 class="club_info-title">{!! __('messages.history') !!}</h1>
	</div>
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
	    <div class="container">
	        <div class="row my-5">
	            <div class="col-lg-12 club_content match_table tournament_history" id="tournament_history">
	                <tournament-history></tournament-history>
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection

@section('page-scripts')
  <script type="text/javascript">
    var tournamentData = {!! json_encode($tournament) !!};
    var competitionList = {!! json_encode($competitionList) !!};
    var allHistoryYears = {!! json_encode($allHistoryYears) !!}
  </script>
  <script type="text/javascript" src="{{ mix('frontend/js/tournamenthistory.js') }}"></script>
@endsection

@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="grid-full club_info">
		<span class="club_info-detail">{!! __('messages.tournament') !!}</span>
		<h1 class="club_info-title">{!! __('messages.history') !!}</h1>
	</div>
@endsection

@section('quick-links')
	@include('partials.frontend.quick-links.tournament')
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
	    <div class="container">
	        <div class="row my-5">
	            <div class="grid-22">
	            </div>
	            <div class="col-lg-8 club_content" id="tournament_history">
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
  <script type="text/javascript" src="{{ mix('assets/js/frontend/tournamenthistory.js') }}"></script>
@endsection

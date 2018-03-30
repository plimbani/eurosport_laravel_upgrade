@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="col-lg-8 club_info">
		<h1 class="club_info-title">{!! __('messages.matches') !!}</h1>
	</div>
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
    <div class="container">
        <div class="row my-5">
            <div class="col-lg-12 club_content match_table {{ $brand_font_class }}" id="matches_list">
                @if($tournament)
                    <match-listing></match-listing>
                @else
                    {!! __('messages.match_schedule_message') !!}
                @endif
            </div>
        </div>
    </div>
	</div>
	<!-- End of content wrapper -->
@endsection

@section('page-scripts')
  <script type="text/javascript">
    var tournamentData = {!! json_encode($tournament) !!};
  </script>
  <script type="text/javascript" src="{{ mix('assets/js/core/matches-plugins.js') }}"></script>
  <script type="text/javascript" src="{{ mix('frontend/js/matchlist.js') }}"></script>
@endsection

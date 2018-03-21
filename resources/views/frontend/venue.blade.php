@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="grid-full club_info">
		<h1 class="club_info-title">{!! __('messages.venue') !!}</h1>
	</div>
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
    <div class="container">
      <div class="row my-5">
        <div class="grid-22">
        </div>
        <div class="col-lg-8 club_content">
					@if(count($markers) > 0)
						<div id="venue_map" style="width:100%;height:400px;"></div>
					@endif
        </div>
      </div>
    </div>
	</div>
	<!-- End of content wrapper -->

	<!-- Content wrapper -->
	<div class="content__wrapper">
    <div class="container">
      <div class="row my-5">
        <div class="grid-22">
        </div>
        <div class="col-lg-8 club_content">
					@if(count($locations) > 0)
						@foreach($locations as $location)
							<div class="col-sm-3">
								<h3>{{ $location->name }}</h3>
								<div>
									{!! nl2br($location->address) !!}
								</div>
							</div>
						@endforeach
					@else
						<p>{!! __('messages.no_locations_found') !!}</p>
					@endif
        </div>
      </div>
    </div>
	</div>
	<!-- End of content wrapper -->
@endsection

@section('page-scripts')
	@if(count($markers) > 0)
		<script>
			var markers = {!! json_encode($markers) !!};
		</script>
		<script src="{{ asset('frontend/js/venue.js') }}"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key={{ config('wot.google_api_key') }}&&callback=venueMap"></script>
	@endif
@endsection

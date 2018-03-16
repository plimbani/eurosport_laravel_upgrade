@extends('layouts.frontend.inner')

@section('content')
	<h1>{!! __('messages.venue') !!}</h1>
	@if(count($markers) > 0)
		<div id="venue_map" style="width:100%;height:400px;"></div>
	@endif
	<div class="container">
		<div class="row">
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
@endsection
@section('page-scripts')
	@if(count($markers) > 0)
		<script>
			var markers = {!! json_encode($markers) !!};
		</script>
		<script src="{{ asset('assets/js/frontend/venue.js') }}"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key={{ config('wot.google_api_key') }}&&callback=venueMap"></script>
	@endif
@endsection
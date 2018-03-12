@extends('layouts.frontend')

@section('content')
	<h1>{!! __('messages.venue') !!}</h1>
	@if(count($markers) > 0)
		<div id="venueMap" style="width:100%;height:400px;"></div>
	@endif
	<div class="container">
		<div class="row">
			@foreach($locations as $location)
			<div class="col-sm-3">
				<h3>{{ $location->name }}</h3>
				<div>
					{!! nl2br($location->address) !!}
				</div>
			</div>
			@endforeach
		</div>
	</div>
@endsection
@section('page-scripts')
	@if(count($markers) > 0)
		<script>
			var markers = {!! json_encode($markers) !!};
		</script>
		<script src="{{ asset('assets/js/venue.js') }}"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key={{ config('wot.google_api_key') }}&&callback=venueMap"></script>
	@endif
@endsection
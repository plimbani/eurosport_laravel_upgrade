@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="col-lg-8 club_info">
		<h1 class="club_info-title">{!! __('messages.venue') !!}</h1>
	</div>
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
	    <div class="container">
	    	@if(count($markers) > 0)
		      	<div class="row my-5">
		        	<div class="col-lg-12 club_content venue-information {{ $brand_font_class }}">
						<div id="venue_map" class="venue-map"></div>
		        	</div>
		      	</div>
	      	@endif
	      	<div class="row mt-2 {{ count($markers) == 0 ? 'my-5' : 'mb-5' }}">
		        <div class="col-lg-12 club_content {{ $brand_font_class }}">
					@if(count($locations) > 0)
						<div class="row">
							@foreach($locations as $location)
									<div class="col-sm-4 col-md-3 col-lg-3">
										<h4 class="my-3 font-weight-bold">{{ $location->name }}</h4>
										<h6>
											{!! nl2br($location->address) !!}
										</h6>
									</div>
							@endforeach
						</div>
					@else
						<div class="no-data h6 text-muted mb-0">{!! __('messages.no_location_found') !!}</div>
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
		<script src="//maps.googleapis.com/maps/api/js?key={{ config('wot.google_api_key') }}&&callback=venueMap"></script>
	@endif
@endsection

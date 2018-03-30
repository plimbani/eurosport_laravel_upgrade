@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="col-lg-8 club_info">
		<h1 class="club_info-title">{!! __('messages.program') !!}</h1>
	</div>
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
	    <div class="container">
	        <div class="row my-5">
	            <div class="col-lg-2">
	            </div>
	            <div class="col-lg-8 club_content list-style-type {{ $brand_font_class }}">
	            	@foreach($itineraries as $itinerary)
	            		<h4 class="text-primary font-weight-bold">{{ $itinerary->name }}</h4>
	            		<hr class="hr m-0 bg-primary">
	            		@if(count($itinerary['items']) > 0)
			            	<ul class="list-group list-group-flush mb-4">
			            		@foreach($itinerary['items'] as $item)
								  	<li class="list-group-item border-0 px-0 py-4">
								  		<div class="row">
								  			<div class="col-4">
								  				<h5 class="mb-0 font-weight-bold">{{ $item->day }}</h5>
								  				<div class="h7">{{ $item->time }}</div>
								  			</div>
								  			<div class="col-8">
												<h5>{{ $item->item }}</h5>
								  			</div>
								  		</div>
								  	</li>
							  	@endforeach
							</ul>
						@else
							<div class="no-data h6 text-muted mb-0 mt-4">No item found.</div>
						@endif
					@endforeach
	            </div>
	            @if(count($itineraries) == 0)
					<div class="no-data col-sm-12 h6 text-muted mb-0 mt-0">No itineraries found.</div>
				@endif
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection

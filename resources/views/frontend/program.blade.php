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
	            <div class="col-lg-3">
	            </div>
	            <div class="col-lg-8 club_content list-style-type">
	            	@foreach($itineraries as $itinerary)
	            		<h4>{{ $itinerary->name }}</h4>
	            		<hr class="hr m-0">
		            	<ul class="list-group list-group-flush mb-4">
		            		@foreach($itinerary['items'] as $item)
							  	<li class="list-group-item px-0 py-4">
							  		<div class="row">
							  			<div class="col-4">
							  				<h5 class="mb-0">{{ $item->day }}</h5>
							  				<div class="h7">{{ $item->time }}</div>
							  			</div>
							  			<div class="col-8">
											<h5>{{ $item->item }}</h5>
							  			</div>
							  		</div>
							  	</li>
						  	@endforeach
						</ul>
					@endforeach
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection

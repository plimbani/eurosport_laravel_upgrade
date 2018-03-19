@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="grid-full club_info">
		<h1 class="club_info-title">{!! __('messages.program') !!}</h1>
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
									@foreach($itineraries as $itinerary)
											<h2>{{ $itinerary->name }}</h2>
											@foreach($itinerary['items'] as $item)
													<div>{{ $item->day }}</div>
													<div>{{ $item->time }}</div>
													<div>{{ $item->item }}</div>
											@endforeach
									@endforeach
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection

@extends('layouts.frontend')

@section('content')
	<h1>{!! __('messages.program') !!}</h1>
	@foreach($itineraries as $itinerary)
		<h2>{{ $itinerary->name }}</h2>
		@foreach($itinerary['items'] as $item)
			<div>{{ $item->day }}</div>
			<div>{{ $item->time }}</div>
			<div>{{ $item->item }}</div>
		@endforeach
	@endforeach

	<div class="row">
		@foreach($additionalPages as $additionalPage)
			@if($additionalPage->is_published == 1)
				<div class="col-sm-4">
					<a href="{{ url($additionalPage->url) }}" class="btn btn-primary w-100">{{ $additionalPage->title }}</a>
				</div>
			@endif
		@endforeach
	</div>
@endsection
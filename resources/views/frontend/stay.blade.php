@extends('layouts.frontend')

@section('content')

<h2>{!! __('messages.stay_introduction_content') !!}</h2>
{!! $stayContent->content !!}
<div class="row">
	<div class="col-sm-4">
		<a href="{{ url('/meals') }}" class="btn btn-primary w-100">{!! __('messages.meals') !!}</a>
	</div>
	<div class="col-sm-4">
		<a href="{{ url('/accommodation') }}" class="btn btn-primary w-100">{!! __('messages.accommodation') !!}</a>
	</div>

@foreach($additionalPages as $additionalPage)
	@if($additionalPage->is_published == 1)
		<div class="col-sm-4">
			<a href="{{ url($additionalPage->url) }}" class="btn btn-primary w-100">{{ $additionalPage->title }}</a>
		</div>
	@endif
@endforeach	
</div>

@endsection
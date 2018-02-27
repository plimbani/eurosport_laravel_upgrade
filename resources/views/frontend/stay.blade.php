@extends('layouts.frontend')

@section('content')

<h1>{!! __('messages.stay') !!}</h1>

{!! $stayContent->content !!}

<div class="row">
	<div class="col-sm-4">
		<a href="{{ url('/meals') }}" class="btn btn-primary w-100">{!! __('messages.meals') !!}</a>
	</div>
	<div class="col-sm-4">
		<a href="{{ url('/accommodation') }}" class="btn btn-primary w-100">{!! __('messages.accommodation') !!}</a>
	</div>

@foreach($additionalPages as $additionalPage)
	<div class="col-sm-4">
		<a href="{{ url($additionalPage->url) }}" class="btn btn-primary w-100">{{ $additionalPage->title }}</a>
	</div>
@endforeach	
</div>

@endsection
@extends('layouts.frontend')

@section('content')

<h1>{!! __('messages.stay') !!}</h1>

{!! $stayContent->content !!}

<div class="row">
	<div class="col-sm-4">
		 <a  type="button" href="{{ url('/meals') }}" class="btn btn-primary w-100">{!! __('messages.meals') !!}</a>
	</div>
	<div class="col-sm-4">
		 <a type="button" href="{{ url('/accommodation') }}" class="btn btn-primary w-100">{!! __('messages.accommodation') !!}</a>
	</div>

@foreach($additionalPageContent as $additional)
	<div class="col-sm-4">
		 <a type="button" class="btn btn-primary w-100">{{ $additional->title }}</a>
	</div>
@endforeach	
</div>

@endsection
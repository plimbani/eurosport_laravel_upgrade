@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="col-lg-8 club_info">
		<h1 class="club_info-title">{!! __('messages.public_transport') !!}</h1>
	</div>
@endsection

@section('quick-links')
	@include('partials.frontend.quick-links.stay')
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
	    <div class="container">
	        <div class="row my-5">
	            <div class="col-lg-2">
	            </div>
	            <div class="col-lg-8 club_content {{ $brand_font_class }}">
					@if($publicTransport)
						<a name="public-transport"></a>
						<h2>{!! __('messages.visitors_public_transport') !!}</h2>
						{!! $publicTransport !!}
					@endif
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection
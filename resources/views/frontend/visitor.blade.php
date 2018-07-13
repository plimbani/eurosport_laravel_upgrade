@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="col-lg-8 club_info">
		<h1 class="club_info-title">{!! __('messages.visitors') !!}</h1>
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
	                @if($arrivalCheckInInformation)
						<h2>{!! __('messages.visitors_arrival_and_check_in') !!}</h2>
						{!! $arrivalCheckInInformation !!}
						@if($publicTransport) <div class="py-4 mb-3"></div> @endif
					@endif
					
					@if($publicTransport)
						<a name="public-transport"></a>
						<h2>{!! __('messages.visitors_public_transport') !!}</h2>
						{!! $publicTransport !!}
						@if($tips) <div class="py-4 mb-3"></div> @endif
					@endif

					@if($tips)
						<a name="tips"></a>
						<h2>{!! __('messages.visitors_tips_for_visitors') !!}</h2>
						{!! $tips !!}
					@endif
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection
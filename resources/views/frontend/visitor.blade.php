@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="col-lg-8 club_info">
		<h1 class="club_info-title">{!! __('messages.visitors') !!}</h1>
	</div>
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
	    <div class="container">
	        <div class="row my-5">
	            <div class="col-lg-3">
	            </div>
	            <div class="col-lg-8 club_content">
	                @if($arrivalCheckInInformation)
						<h2>{!! __('messages.visitors_arrival_and_check_in') !!}</h2>
						{!! $arrivalCheckInInformation !!}
					@endif

					@if($publicTransport)
					<h2>{!! __('messages.visitors_public_transport') !!}</h2>
					{!! $publicTransport !!}
					@endif

					@if($tips)
					<h2>{!! __('messages.visitors_tips_for_visitors') !!}</h2>
					{!! $tips !!}
					@endif
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection
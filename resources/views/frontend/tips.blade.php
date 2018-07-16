@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="col-lg-8 club_info">
		<h1 class="club_info-title">{!! __('messages.tips') !!}</h1>
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
@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="col-lg-8 club_info">
		<span class="club_info-detail">{!! __('messages.stay') !!}</span>
		<h1 class="club_info-title">{!! __('messages.meals') !!}</h1>
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
	            <div class="col-lg-3">
	            </div>
	            <div class="col-lg-8 club_content">
	                {!! $mealsContent->content !!}
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection
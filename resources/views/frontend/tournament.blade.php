@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="col-lg-8 club_info">
		<h1 class="club_info-title">{!! __('messages.tournament') !!}</h1>
	</div>
@endsection

@section('quick-links')
	@include('partials.frontend.quick-links.tournament')
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
	    <div class="container">
	        <div class="row my-5">
	            <div class="col-lg-12 club_content match_table {{ $brand_font_class }}">
	            	<div class="table-responsive tounament-data-table">
	                	{!! $tournamentContent->content !!}
	            	</div>
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection

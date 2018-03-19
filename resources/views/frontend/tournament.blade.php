@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="grid-full club_info">
		<h1 class="club_info-title">{!! __('messages.tournament') !!}</h1>
	</div>
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
	    <div class="container">
	        <div class="row my-5">
	            <div class="grid-22">
	            </div>
	            <div class="col-lg-8 club_content">
	                {!! $tournamentContent->content !!}
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection

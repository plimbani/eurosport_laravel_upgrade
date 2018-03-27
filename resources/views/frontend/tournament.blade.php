@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="col-lg-8 club_info">
		<h1 class="club_info-title">{!! __('messages.tournament') !!}</h1>
	</div>
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
	    <div class="container">
	        <div class="row my-5">
	            <div class="col-lg-12 club_content match_table">
	            	<div class="table-responsive">
	                	{!! $tournamentContent->content !!}
	            	</div>
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection

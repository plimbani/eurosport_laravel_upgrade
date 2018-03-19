@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="grid-full club_info">
		<span class="club_info-detail">{!! __('messages.program') !!}</span>
		<h1 class="club_info-title">{{ $additionalPage->title }}</h1>
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
	                {!! $additionalPage->content !!}
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection

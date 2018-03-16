@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="grid-full club_info">
		<span class="club_info-detail">{!! __('messages.tournament') !!}</span>
		<h1 class="club_info-title">{!! __('messages.rules') !!}</h1>
	</div>
@endsection

@section('quick-links')
	<li class="nav-item">
		<a class="nav-link active" href="#">
			<span class="mr-2"><i class="fas fa-utensils"></i></span>
			<span>Meals</span>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#">
			<span class="mr-2"><i class="fas fa-bed"></i></span>
			<span>Accommodation</span>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#">
			<span class="mr-2"><i class="fas fa-file-alt"></i></span>
			<span>Lorem ipsum dolor</span>
		</a>
	</li>
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
	    <div class="container">
	        <div class="row my-5">
	            <div class="grid-22">
	            </div>
	            <div class="col-lg-8 club_content">
	                {!! $rulesContent->content !!}
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection

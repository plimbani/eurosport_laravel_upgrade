@extends('layouts.frontend.inner')

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

{{-- <h1>{!! __('messages.rules') !!}</h1> --}}

@endsection
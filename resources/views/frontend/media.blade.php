@extends('layouts.frontend.inner')

@section('plugin-styles')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" />
@endsection

@section('hero-section')
	<div class="grid-full club_info">
		<span class="club_info-detail">{!! __('messages.visitors') !!}</span>
		<h1 class="club_info-title">{!! __('messages.tourist_information') !!}</h1>
	</div>
@endsection

@section('content')
	<!-- Gallery -->
    <div class="content__wrapper">
        <div class="container">
            <div class="row my-5">
            	@if(count($photos) > 0)
            		@foreach($photos as $photo)
		                <div class="col-6 col-md-4 col-lg-3">
		                    <a class="figure text-center" href="{{ $photo->image('large') }}" data-toggle="lightbox" data-gallery="example-gallery">
		                        <img src="{{ $photo->image('thumbnail') }}" class="figure-img img-fluid ml-1" alt="{{ $photo->caption }}">
		                        <figcaption class="figure-caption">{{ $photo->caption }}</figcaption>
		                    </a>
		                </div>
		            @endforeach
                @else
                	<p>{!! __('messages.no_photos_found') !!}</p>
                @endif
            </div>
        </div>
    </div>
    <!-- End of gallery -->
@endsection

@section('page-scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js"></script>
	<script src="{{ asset('frontend/js/media.js') }}"></script>
@endsection
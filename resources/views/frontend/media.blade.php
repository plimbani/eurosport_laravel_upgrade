@extends('layouts.frontend')
@section('plugin-styles')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" />
@endsection
@section('content')
	<div class="row">
		@if(count($photos) > 0)
			@foreach($photos as $photo)
				<div class="col-sm-4">
					<a href="{{ $photo->image('large') }}" data-toggle="lightbox" data-title="{{ $photo->caption }}" data-gallery="example-gallery">		
						<img src="{{ $photo->image('thumbnail') }}">
						<p>{{ $photo->caption }}</p>
					</a>
				</div>
			@endforeach
		@else
			<p>{!! __('messages.no_photos_found') !!}</p>
		@endif
	</div>
@endsection
@section('page-scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js"></script>
	<script type="text/javascript">
		$(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
      	alwaysShowClose: true
      });
  	});
	</script>
@endsection
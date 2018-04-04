@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="col-lg-8 club_info">
		<h1 class="club_info-title">{!! __('messages.teams') !!}</h1>
	</div>
@endsection

@section('content')
	<!-- Content wrapper -->
	<div class="content__wrapper">
	    <div class="container">
	        <div class="row my-5">
	            <div class="col-lg-12 club_content tournament-list {{ $brand_font_class }}">
					<div class="row">
						@foreach($ageCategories as $category)
							<div class="col-sm-6 col-md-3 col-lg-3 mb-5">
								<h4 class="mb-0 text-primary font-weight-bold">{{ $category->name }}</h4>
								<hr class="hr mt-0 mb-0 bg-primary">
								<div class="js-list-parent-div">
									@if(count($category->teams) > 0)
										<ul class="js-list list-unstyled">
											@foreach($category->teams as $team)
												<li class="team-item d-flex justify-content-between">{{ $loop->index + 1 }}. {{ $team->name }} ({{ $team->country->country_code }})
													<span class="flag-icon flag-icon-{{ $team->country->country_flag }}"></span>
													{{-- <div class="t-shirt-icon">
														<div class="icon-image-overlay">
															<img src="{{ asset('/frontend/images/tshirt.png') }}">
														</div>
														<span class="flag-icon flag-icon-{{ $team->country->country_flag }}"></span>
													</div> --}}
												</li>
											@endforeach
										</ul>
									@else
										<div class="no-data h6 text-muted mb-0">{!! __('messages.no_team_found') !!}</div>
									@endif
								</div>
							</div>
						@endforeach
						@if(count($ageCategories) == 0)
							<div class="no-data col-sm-12 h6 text-muted mb-0 mt-0">{!! __('messages.no_age_category_found') !!}</div>
						@endif
					</div>
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection

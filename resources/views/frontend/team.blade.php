@extends('layouts.frontend.inner')

@section('hero-section')
	<div class="grid-full club_info">
		<h1 class="club_info-title">{!! __('messages.teams') !!}</h1>
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
									<div class="row">
											@foreach($ageCategories as $category)
													<div class="col-sm-3">
															<h3>{{ $category->name }}</h3>
															<div>
																	<ul class="js-list">
																			@foreach($category->teams as $team)
																					<li class="team-item">{{ $team->name }} ({{ $team->country->country_code }}) <span class="flag-icon flag-icon-{{ $team->country->country_flag }}"></span></li>
																			@endforeach
																	</ul>
															</div>
													</div>
											@endforeach
									</div>
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection

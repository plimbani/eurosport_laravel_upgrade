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
	            {{-- <div class="col-lg-3">
	            </div> --}}
	            <div class="col-lg-12 club_content tournament-list">
					<div class="row">
						@foreach($ageCategories as $category)
							<div class="col-sm-3">
								<h3 class="mb-0">{{ $category->name }}</h3>
								<hr class="hr mt-0 mb-3">
								<div>
									<ul class="js-list">
										@foreach($category->teams as $team)
											<li class="team-item"><span>{{ $team->name }} ({{ $team->country->country_code }})</span> <span class="flag-icon pull-right flag-icon-{{ $team->country->country_flag }}"></span></li>
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

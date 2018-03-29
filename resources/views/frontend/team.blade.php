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
							<div class="col-sm-3 mb-5">
								<h3 class="mb-0 text-primary font-weight-bold">{{ $category->name }}</h3>
								<hr class="hr mt-0 mb-0 bg-primary">
								<div class="js-list-parent-div">
									@if(count($category->teams) > 0)
										<ul class="js-list list-unstyled">
											@foreach($category->teams as $team)
												<li class="team-item d-flex justify-content-between">{{ $team->name }} ({{ $team->country->country_code }}) <span class="flag-icon flag-icon-{{ $team->country->country_flag }}"></span></li>
											@endforeach
										</ul>
									@else
										<div class="no-data h6"> No team found.</div>
									@endif
								</div>
							</div>
						@endforeach
						@if(count($ageCategories) == 0)
							No age category found.
						@endif
					</div>
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End of content wrapper -->
@endsection

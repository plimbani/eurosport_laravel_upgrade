@extends('layouts.frontend')

@section('content')
	<h2 class="text-center">{!! __('messages.teams') !!}</h2>
	<div class="container teams-page">
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
@endsection
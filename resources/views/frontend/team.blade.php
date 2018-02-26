@extends('layouts.frontend')

@section('content')
	<h2 class="text-center">Teams</h2>
	<div class="container">
		<div class="row">
			@foreach($ageCategories as $category)
			<div class="col-sm-3">
				<h3>{{ $category->name }}</h3>
				@foreach($category->teams as $team)
					<p>{{ $team->name }} ({{ $team->country->country_code }}) <span class="flag-icon flag-icon-{{ $team->country->country_flag }}"></span></p>
				@endforeach
			</div>
			@endforeach
		</div>
	</div>

@endsection
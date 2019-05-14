<html lang="en">
	<head>
		<title>Euro-Sportring Administration</title>
		{{-- <link href="{{ asset(mix('assets/css/laraspace.css')) }}" rel="stylesheet" type="text/css" /> --}}
		{{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">  --}}
		<link type="text/css" rel="stylesheet" href="{{ asset(mix('/assets/css/flag_icons.css')) }}"/>
	</head>
	<body class="layout-default skin-default pace-done" data-gr-c-s-loaded="true">
		<div class="container">
			<center>
				@if($tournamentLogo != null)	
			  		<img src="{{ $tournamentLogo }}" id="logo-desk" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px" height="100px">
			  	@endif

			  	@if($tournamentLogo == null && config('config-variables.current_layout') == 'tmp')
		  			<img src="{{ asset('assets/img/tmplogo.svg')}}" id="logo-desk" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px" height="100px">
			  	@endif

			  	@if($tournamentLogo == null && config('config-variables.current_layout') == 'commercialisation')
			  		<img src="{{ asset('assets/img/easy-match-manager.jpg')}}" id="logo-desk" alt="Laraspace Logo" class="hidden-sm-down text-center" width="200px" height="100px">
			  	@endif
			</center>
			<div class="row">
				@foreach($groupsData as $group)
				<div class="col-sm-2 my-2">
					<div class="card-content bg-white">
						<span class="card-title text-primary">
							<strong>
								@php
									$groupNameStr = explode("-", $group['name']);
									$competitionType = $groupNameStr[0];
								@endphp
								@if($competitionType == 'PM')
						        	{{ str_replace('Group-', '', $group['groups']['group_name']) }}
						        @else
						        	{{ $group['groups']['group_name'] }}
								@endif
							</strong>
						</span>
						@for ($i = 1; $i <= $group['group_count']; $i++)
							<div>
								<p class="text-primary left">
									<strong>
										@php
											$fullName = '';
											if(isset($group['groups']['actual_group_name'])) {
									          $actualGroupName = $group['groups']['actual_group_name'];
									          $fullName = $actualGroupName. '-' .$i;
									        } else {
									          $fullName = $group['groups']['group_name']. $i;
									        }

									        $displayName = $fullName;
										@endphp
										@foreach($teamsData as $team)
											@if($team->age_group_id == $data['ageCategoryId'] && $fullName == $team->group_name)
												@php $displayName = 'flag-icon flag-icon-' .$team->country_flag; @endphp
											@endif
										@endforeach
										<span class="{{ $displayName }}"></span>
										
										<span>
											@php
												$fullName = "";
												$actualFullName = "";
											@endphp

											@if(isset($group['groups']['actual_group_name']))
												@php
													$actualFullName = $group['groups']['actual_group_name']. '-' .$i;
													$actualGroupName = explode("-", $group['groups']['actual_group_name']);
													$fullName = $actualGroupName[0]. '-' .$i;
												@endphp
											@else
												@php
													$fullName = $actualFullName = $group['groups']['group_name'] .$i;
												@endphp
											@endif

											@foreach($teamsData as $team)
												@if($team->age_group_id == $data['ageCategoryId'] && $actualFullName == $team->group_name)
													@php $fullName = $team->name; @endphp
												@endif
											@endforeach

											{{ $fullName }}
										</span>
									</strong>
								</p>
							</div>
						@endfor
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</body>
</html>
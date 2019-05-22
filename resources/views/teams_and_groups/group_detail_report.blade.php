<html lang="en">
	<head>
		<title>Euro-Sportring Administration</title>
		<link type="text/css" rel="stylesheet" href="{{ asset(mix('/assets/css/flag_icons.css')) }}"/>
		<style>
			.logo {
				width: 150px;
			}

			.data-wrapper {
				margin-top: 25px;
				background: #f8f8f8;
				padding: 1.25em;
			}

			.card-content {
				background: white;
				padding: 0.75rem;
	    		position: relative;
			    border-radius: 2px;
			    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);
	    	}

	    	.card-content .card-title {
			    display: block;
			    line-height: 32px;
			    margin-bottom: 8px;
			    font-size: 18px;
    			font-weight: 300;
    			color: #2196F3;
    			text-align: center;
			}

			.card-content p {
				font-size: 12px;
			}

			table tr {
				page-break-inside: avoid;
				vertical-align: top;
			}

		</style>
	</head>
	<body>
		<center>
			@if($tournamentLogo != null)	
		  		<img src="{{ $tournamentLogo }}" class="hidden-sm-down text-center" id="logo-desk" alt="Laraspace Logo" width="200px" height="100px">
		  	@endif

		  	@if($tournamentLogo == null && config('config-variables.current_layout') == 'tmp')
	  			<img src="{{ asset('assets/img/tmplogo.svg')}}" class="hidden-sm-down text-center" id="logo-desk" alt="Laraspace Logo" width="200px" height="100px">
		  	@endif

		  	@if($tournamentLogo == null && config('config-variables.current_layout') == 'commercialisation')
		  		<img src="{{ asset('assets/img/easy-match-manager.jpg')}}" class="hidden-sm-down text-center" id="logo-desk" alt="Laraspace Logo" width="200px" height="100px">
		  	@endif
			<h3>{{ $categoryName. ' groups' }}</h3>
		</center>

		<div class="data-wrapper">
			<table style="width:100%">
				@foreach($groupsData as $group)
					@if($loop->index % 3 === 0)
						<tr>
					@endif
						<td align="center">
							<div class="card-content">
								<div class="card-title">
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
								</div>
								@for ($i = 1; $i <= $group['group_count']; $i++)
									<div>
										<p>
											<strong>
												@php
													$isTeamAllocated = 0;
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
														@php
														$isTeamAllocated = 1; 
														$displayName = 'flag-icon flag-icon-' .$team->country_flag; 
														@endphp
													@endif
												@endforeach

												@if($isTeamAllocated == 1) 
													<img width="16" height="12" src="{{ asset($team->logo) }}" alt="">
												@else
													<span class="{{ $displayName }}"></span>
												@endif
												
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
						</td>
					@if($loop->index % 3 === 2)
						</tr>
					@endif
				@endforeach
			</table>
		</div>
	</body>
</html>
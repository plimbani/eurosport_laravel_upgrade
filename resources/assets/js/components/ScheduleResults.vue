<template>
	<div class="tab-content summary-report-content">
		<h6><strong>{{$lang.summary_schedule}}</strong></h6>
		<span>{{$lang.summary_schedule_last_update}}</span>
		<div class="row mt-4">
			<div class="col-md-8">
				<ul class="schedule_list">
					<li class="active"><a href="">{{$lang.summary_schedule_draws}}</a></li>
					<li><a href="">{{$lang.summary_schedule_matches}}</a></li>
					<li><a href="">{{$lang.summary_schedule_teams}}</a></li>
				</ul>
				<!-- <button type="button" class="btn btn-primary">Draw</button>
				<button type="button" class="btn btn-primary">Matches</button>
				<button type="button" class="btn btn-primary">Teams</button> -->
			</div>
			<div class="col-md-4">
				<button type="button" class="btn btn-primary pull-right">{{$lang.summary_schedule_button_print}}</button>
			</div>
		</div>
		<div class="col-md-6 row mt-4">
			<table class="table draw_table">
				<thead>
                    <tr>
                        <th>{{$lang.summary_schedule_draw}}</th>
                        <th>{{$lang.summary_schedule_type}}</th>
                        <th>{{$lang.summary_schedule_team}}</th>
                    </tr>
                </thead>
                <tbody>
                	<tr>
                		<td> 
                			<a href=""> U15 - Group A </a>
                		</td>
                		<td>Round robin</td>
                		<td>10</td>
                	</tr>
                	<tr>
                		<td> 
                			<a href=""> U15 - Group A </a>
                		</td>
                		<td>Round robin</td>
                		<td>10</td>
                	</tr>
                	<tr>
                		<td> 
                			<a href=""> U15 - PM </a>
                		</td>
                		<td>Round robin</td>
                		<td>10</td>
                	</tr>
                	<tr>
                		<td>Etc...</td>
                		<td>Etc...</td>
                		<td>Etc...</td>
                	</tr>
                </tbody>
			</table>
		</div>
		<table class="table match_overview" style="display: none;">
			<thead>
				<th>Time</th>
				<th>Draw</th>
				<th>score</th>
				<th>Location</th>
			</thead>

			<tbody>
				<tr>
					<td>9:00</td>
					<td class="text-center">
						<a href="" class="pull-left text-left">U12 - Group A</a>
						<a href="" class="">
							<span>SV Heimstetten U12 <img src="/assets/img/flag.png" width="20">&nbsp; - &nbsp; 
								  <img src="/assets/img/flag.png" width="20"> CS Pays Vert 1
							</span>
						</a>
					</td>
					<td>0-2</td>
					<td><a href="">Sportpark Heimstetten - Pitch 5</a></td>
				</tr>
			</tbody>
		</table>
	</div>
</template>
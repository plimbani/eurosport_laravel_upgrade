<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block">
				<div class="clearfix">
	  				<div class="pull-left">
		  				<div class="mt-4"><strong>Pitch Planner</strong></div>
		  			</div>
		  			<div class="pull-right">
		  				<form class="form-inline filter-category-form">
		  					<div class="form-group">
		  						<label for="nameInput" class="control-label"><strong>Filter by:</strong></label> 
		  						<label class="radio-inline control-label">
	  								<input type="radio" name="gender" value="team" checked="checked"> Team
	                            </label>
	                             <label class="radio-inline control-label">
	                            	<input type="radio" name="gender" value="country"> Location
	                            </label>
	                            <label class="radio-inline control-label">
	                            	<input type="radio" name="gender" value="age category"> Age Category
	                            </label>
	                            <select class="form-control ls-select2">
		                            <option value="">Select a team</option>
		                            <option value="">-------</option>
		                            <option value="">Etc...</option>
		                        </select>
		                        <label class="control-label">
		                        	<a href="#">Clear</a>
		                        </label>
		  					</div> 
		  					
		  				</form>
		  			</div>
	  			</div>

	  			<div class="mt-4">
	  				<div class="row">
	  					<div class="col-md-9">
	  						<div class="pitch_planner_div">
			  					<table class="table pitch_planner_table">
			  						<thead>
			  							<tr>
			  								<th colspan="5" class="text-center" style="border-right:0">Stage 1</th>
			  							</tr>
			  							<tr>
			  								&nbsp;
			  							</tr>
			  							<tr>
			  								<th class="text-center">TIme</th>
			  								<th class="text-center">Pitch 1</th>
			  								<th class="text-center">Pitch 2</th>
			  								<th class="text-center">Pitch 3</th>
			  								<th class="text-center">Etc...</th>
			  							</tr>
			  						</thead>
			  						<tbody>
			  							<tr>
			  								<td>8:00</td>
			  								<td></td>
			  								<td></td>
			  								<td></td>
			  								<td></td>
			  							</tr>
			  							<tr>
			  								<td>8:30</td>
			  								<td></td>
			  								<td></td>
			  								<td></td>
			  								<td></td>
			  							</tr>
			  							<tr>
			  								<td>9:00</td>
			  								<td>
			  									<div class="grey_bg text-center">
			  										<span class="clearfix">
			  											<label class="pull-left">R</label>
			  											<label class="pull-right"><i class="fa fa-exclamation-triangle"></i></label>
			  										</span>
			  										<span>U19-RR1-01</span>
			  										<span>U19-A-1 v U19-A-2</span>
			  										<span>09:00 (45 mins)</span>
			  									</div>
			  								</td>
			  								<td></td>
			  								<td>
			  									<div class="dark_grey_bg text-center">
			  										Unavailable 60 mins
			  									</div>
			  								</td>
			  								<td></td>
			  							</tr>
			  							<tr>
			  								<td>9:30</td>
			  								<td>
			  									<div class="green_bg text-center">
			  										<span>U19-RR1-01</span>
			  										<span>U19-A-1 v U19-A-2</span>
			  										<span>09:00 (45 mins)</span>
			  									</div>
			  								</td>
			  								<td></td>
			  								<td>
			  									<div class="yellow_bg text-center">
			  										<span>U19-RR1-01</span>
			  										<span>U19-A-1 v U19-A-2</span>
			  										<span>09:00 (45 mins)</span>
			  									</div>
			  								</td>
			  								<td></td>
			  							</tr>
			  							<tr>
			  								<td>10:00</td>
			  								<td></td>
			  								<td>
			  									<div class="yellow_bg text-center">
			  										<span>U19-RR1-01</span>
			  										<span>U19-A-1 v U19-A-2</span>
			  										<span>09:00 (45 mins)</span>
			  									</div>
			  								</td>
			  								<td></td>
			  								<td></td>
			  							</tr>
			  							<tr>
			  								<td>10:30</td>
			  								<td>
			  									<div class="yellow_bg text-center">
			  										<span>U19-RR1-01</span>
			  										<span>U19-A-1 v U19-A-2</span>
			  										<span>09:00 (45 mins)</span>
			  									</div>
			  								</td>
			  								<td></td>
			  								<td></td>
			  								<td></td>
			  							</tr>
			  						</tbody>
			  					</table>
			  					<table class="table pitch_planner_table">
			  						<thead>
			  							<tr>
			  								<th colspan="5" class="text-center" style="border-right:0">Stage 2</th>
			  							</tr>
			  							<tr>
			  								&nbsp;
			  							</tr>
			  							<tr>
			  								<th class="text-center">TIme</th>
			  								<th class="text-center">Pitch 1</th>
			  								<th class="text-center">Pitch 2</th>
			  								<th class="text-center">Pitch 3</th>
			  								<th class="text-center">Etc...</th>
			  							</tr>
			  						</thead>
			  						<tbody>
			  							<tr>
			  								<td>8:00</td>
			  								<td></td>
			  								<td></td>
			  								<td></td>
			  								<td></td>
			  							</tr>
			  							<tr>
			  								<td>8:30</td>
			  								<td></td>
			  								<td></td>
			  								<td></td>
			  								<td></td>
			  							</tr>
			  							<tr>
			  								<td>9:00</td>
			  								<td>
			  									<div class="grey_bg text-center">
			  										<span>U19-RR1-01</span>
			  										<span>U19-A-1 v U19-A-2</span>
			  										<span>09:00 (45 mins)</span>
			  									</div>
			  								</td>
			  								<td></td>
			  								<td>
			  									<div class="dark_grey_bg text-center">
			  										Unavailable 60 mins
			  									</div>
			  								</td>
			  								<td></td>
			  							</tr>
			  							<tr>
			  								<td>9:30</td>
			  								<td>
			  									<div class="green_bg text-center">
			  										<span>U19-RR1-01</span>
			  										<span>U19-A-1 v U19-A-2</span>
			  										<span>09:00 (45 mins)</span>
			  									</div>
			  								</td>
			  								<td></td>
			  								<td>
			  									<div class="yellow_bg text-center">
			  										<span>U19-RR1-01</span>
			  										<span>U19-A-1 v U19-A-2</span>
			  										<span>09:00 (45 mins)</span>
			  									</div>
			  								</td>
			  								<td></td>
			  							</tr>
			  							<tr>
			  								<td>10:00</td>
			  								<td></td>
			  								<td>
			  									<div class="yellow_bg text-center">
			  										<span>U19-RR1-01</span>
			  										<span>U19-A-1 v U19-A-2</span>
			  										<span>09:00 (45 mins)</span>
			  									</div>
			  								</td>
			  								<td></td>
			  								<td></td>
			  							</tr>
			  							<tr>
			  								<td>10:30</td>
			  								<td>
			  									<div class="yellow_bg text-center">
			  										<span>U19-RR1-01</span>
			  										<span>U19-A-1 v U19-A-2</span>
			  										<span>09:00 (45 mins)</span>
			  									</div>
			  								</td>
			  								<td></td>
			  								<td></td>
			  								<td></td>
			  							</tr>
			  						</tbody>
			  					</table>
		  					</div>
	  					</div>
	  					<div class="col-md-3">
	  						<div class="grey_bg">
		  						<div class="tabs tabs-primary planner_list">
		  							<ul class="nav nav-tabs" role="tablist">
		  								<li class="nav-item active">
		  									<a href="" data-toggle="tab" @click="currentView='gamesTab'">Games</a>
		  								</li>
		  								<li class="nav-item">
		  									<a href="" data-toggle="tab" @click="currentView='refereesTab'">Referees</a>
		  								</li>
		  							</ul>
		  							<component :is="currentView"> </component>
		  						</div>
		  					</div>
	  					</div>
	  				</div>
	  			</div>
			</div>
		</div>
	</div>
</template>

<script type="text/babel">
	import GamesTab from '../../../components/GamesTab.vue'
	import RefereesTab from '../../../components/RefereesTab.vue'

	export default {
    data() {
       return {
         currentView:'gamesTab'
       }
    },	
    components: {
        GamesTab, RefereesTab
    }  
}
</script>
<template>
<div class="row">
	<div class="col-md-12">
	</div>
	<div class="col-md-12">
	<table class="table table-hover table-bordered" v-if="matchData.length > 0">
		<thead>
			<th>{{$lang.teams_team_label}}</th>
			<th>{{$lang.teams_categories_label}}</th>
		</thead>
		<tbody>
			<tr v-for="team in paginated('teamlist')">
				<td>
					<div class="matchteam-details">
      			<span :class="'matchteam-flag flag-icon flag-icon-'+team.countryFlag"></span>
						<div class="matchteam-dress" v-if="team.shorts_color && team.shirt_color">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="team.shorts_color" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="team.shirt_color" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
						</div>
						<span class="text-center matchteam-name"><a class="text-primary" href="" @click.prevent="changeTeam(team.id, team.name)">{{team.name}}</a></span>
					</div>
					<!-- <a  href="" @click.prevent="changeTeam(team.id, team.name)"> -->
						<!--<img :src="team.logo" width="20">-->
					<!-- </a> -->
				</td>
				<td class="text-center">
					<a href="" class="text-primary pull-left text-left" @click.prevent="changeGroup(team)">
					<u>{{team.competationName}}</u></a>
				</td>
			</tr>
		</tbody>
	</table>
	<span v-else>No information to display</span>
		<paginate  name="teamlist" :list="matchData" ref="paginator" :per="no_of_records"  class="paginate-list">
	    </paginate>
	    <div class="row d-flex flex-row align-items-center">
	        <div class="col page-dropdown">
	            <select class="form-control ls-select2" name="no_of_records" v-model="no_of_records">
		          	<option v-for="recordCount in recordCounts" v-bind:value="recordCount">
		              {{ recordCount }}
		         	 </option>
	       		 </select>
	      	</div>
	      	<div class="col">
		        <span v-if="$refs.paginator">
		          Viewing {{ $refs.paginator.pageItemsCount }} results
		        </span>
	      	</div>
	     	<div class="col-md-6">
		        <paginate-links for="teamlist"
		          :show-step-links="true" :limit="2" :async="true" class="mb-0">
		        </paginate-links>
	      	</div>
	    </div>
	</div>
</div>
</template>
<script type="text/babel">


import TeamDetails from './TeamDetails.vue'
import DrawDetails from './DrawDetails.vue'
import VuePaginate from 'vue-paginate'

export default {
	props:['matchData'],
	components: {
		TeamDetails, DrawDetails
	},
	data() {
	return {
	      paginate: ['teamlist'],
	      shown: false,
	      no_of_records: 20,
	      recordCounts: [5,10,20,50,100]
		}
	},
	methods: {
		changeTeam(Id, Name) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','teamDetails')
			this.$root.$emit('changeComp',Id,Name);
			//this.$emit('changeComp', Id);
		},
		changeGroup(team) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','drawDetails')
			let Id = team.competationId
			let Name = team.competationName
      let CompetationType = team.competation_type
			this.$root.$emit('changeComp',Id, Name,CompetationType);
			//this.$emit('changeComp',Id);
		},

	}
}
</script>

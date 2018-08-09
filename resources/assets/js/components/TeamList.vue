<template>
<div class="row">
	<div class="col-md-12">
	</div>
	<div class="col-md-12">
	<table class="table table-hover table-bordered" v-if="matchData.length > 0">
		<thead>
			<th class="text-center">{{$lang.teams_team_label}}</th>
			<th class="text-center">{{$lang.teams_categories_label}}</th>
		</thead>
		<tbody>
			<tr v-for="team in paginated('teamlist')">
				<td>
					<div class="matchteam-details">
      			<span :class="'matchteam-flag flag-icon flag-icon-'+team.countryFlag"></span>
						<div class="matchteam-dress" v-if="team.shorts_color && team.shirt_color">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><g><polygon v-bind:fill="team.shorts_color" points="13.2 40 13.2 62 30.2 62 32.2 56 34.2 62 51.2 62 51.2 40 13.2 40"/></g><path v-bind:fill="team.shirt_color" d="M63.81,10.81,51.2,0h-13a6.5,6.5,0,0,1-6,4,6.5,6.5,0,0,1-6-4h-13L.59,10.81A1.7,1.7,0,0,0,.5,13.3L7.2,20l6-4V40h38V16l6,4,6.7-6.7A1.7,1.7,0,0,0,63.81,10.81Z"/></g></svg>
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
import LocationList from './LocationList.vue'
import VuePaginate from 'vue-paginate'

export default {
	props:['matchData'],
	components: {
		TeamDetails, DrawDetails,LocationList
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

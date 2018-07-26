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
					<!-- <a  href="" @click.prevent="changeTeam(team.id, team.name)"> -->
						<!--<img :src="team.logo" width="20">-->
          			<span :class="'flag-icon flag-icon-'+team.countryFlag"></span>
					<span class="text-center"><a class="text-primary" href="" @click.prevent="changeTeam(team.id, team.name)">{{team.name}}</a></span>
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

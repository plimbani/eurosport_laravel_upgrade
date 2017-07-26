<template>
<div class="row">
	<div class="col-md-12">
	</div>
	<div class="col-md-12">
	<table class="table table-hover table-bordered" v-if="matchData.length > 0">
		<thead>
			<th class="text-center">Team</th>
			<th class="text-center">Categories</th>
		</thead>
		<tbody>
			<tr v-for="team in matchData">
				<td>
					<!-- <a  href="" @click.prevent="changeTeam(team.id, team.name)"> -->
						<!--<img :src="team.logo" width="20">-->
          			    <span :class="'flag-icon flag-icon-'+team.countryFlag"></span>
						<span class="text-center">{{team.name}}</span>
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
	</div>
</div>
</template>
<script type="text/babel">


import TeamDetails from './TeamDetails.vue'
import DrawDetails from './DrawDetails.vue'
import LocationList from './LocationList.vue'

export default {
	props:['matchData'],
	components: {
		TeamDetails, DrawDetails,LocationList
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

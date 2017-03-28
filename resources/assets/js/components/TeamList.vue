<template>
<div class="col-md-6 row mt-4">
<h3> Teams </h3>
<table class="table match_overview">
	<thead>
		<th>Team</th>
		<th>Draw</th>
	</thead>
	<tbody>
		<tr v-for="team in matchData">
			<td><img :src="team.logo" width="20">&nbsp;
			<a href="" @click.prevent="teamDetails(team.id)">{{team.name}}</a></td>
			<td class="text-center">
				<a href="" class="pull-left text-left" 
				@click.prevent="changeGroup()">{{team.age_name}} - {{team.assigned_group}}</a>
			</td>
		</tr>
	</tbody>
</table>
</div>
</template>
<script type="text/babel">

import Tournament from '../api/tournament.js'
import TeamDetails from './TeamDetails.vue'
import TeamList from './TeamList.vue'

export default {
	props:['matchData'],
	methods: {
		changeLocation(Id) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','locationList')
			this.$root.$emit('changeComp',Id);
			//this.$store.dispatch('setCurrentScheduleView','locationList')
		},
		changeTeam(Id) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','teamDetails')
			//this.$emit('changeComp', Id);
		},
		changeDrawDetails(Id) {
			// here we dispatch Method
			this.$store.dispatch('setCurrentScheduleView','drawDetails')
			//this.$emit('changeComp',Id);
		},
				
	}	
}
</script>
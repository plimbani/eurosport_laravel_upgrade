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
			<a href="" @click.prevent="changeTeam(team.id, team.name)">{{team.name}}</a></td>
			<td class="text-center">
				<a href="" class="pull-left text-left" 
				@click.prevent="changeGroup(team)">
				{{team.age_name}}-{{team.competationName}}</a>
			</td>
		</tr>
	</tbody>
</table>
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
			Id = team.id
			Name = team.age_name+'-'+team.competationName
			alert(Name)
			this.$root.$emit('changeComp',Id, Name);
			//this.$emit('changeComp',Id);
		},
				
	}	
}
</script>
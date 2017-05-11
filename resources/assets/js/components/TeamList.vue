<template>
<div class="row">
	<div class="col-md-12">
	</div>
	<div class="col-md-12">
	<table class="table table-hover table-bordered" v-if="matchData.length > 0">
		<thead>
			<th>Team</th>
			<th>Draw</th>
		</thead>
		<tbody>
			<tr v-for="team in matchData">
				<td>
					<a class="text-center text-primary" href="" @click.prevent="changeTeam(team.id, team.name)">
						<img :src="team.logo" width="20">
						<span><u>{{team.name}}</u></span>
					</a>
				</td>
				<td class="text-center">
					<a href="" class="pull-left text-left"
					@click.prevent="changeGroup(team)">
					{{team.competationName}}</a>
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
			this.$root.$emit('changeComp',Id, Name);
			//this.$emit('changeComp',Id);
		},

	}
}
</script>

<template>
<div>

<table class="table table-hover table-bordered" v-if="standingData.length > 0">
	<thead>
		<th></th>
		<th></th>
		<th>Points</th>
		<th>Played</th>
		<th>Won</th>
		<th>Draws</th>
		<th>Lost</th>
		<th>Goals For</th>
		<th>Goals Against</th>
	</thead>
	<tbody>
		<tr v-for="stand in standingData">
		<td></td>
			<td align="left">
				<a href="" @click.prevent="changeTeam(stand.team_id, stand.name)">
					 <img :src="stand.teamFlag" width="20">
					<span>
					{{stand.name}}
					</span>
				</a>
			</td>
			<td>{{stand.points}}</td>
			<td>{{stand.played}}</td>
			<td>{{stand.won}}</td>
			<td>{{stand.draws}}</td>
			<td>{{stand.lost}}</td>
			<td>{{stand.goal_for}}</td>
			<td>{{stand.goal_against}}</td>
		</tr>
	</tbody>
</table>
<span v-else>No information available</span>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'

export default {
	props: ['currentCompetationId'],
	data() {
		return {
			standingData:[],
		}
	},
	mounted() {
		// here we call function to get all the Draws Listing
		this.getData()
	},
	methods: {
		getData() {
			if(this.currentCompetationId != 0) {

				let TournamentId = this.$store.state.Tournament.tournamentId
				let tournamentData = {'tournamentId': TournamentId,
			'competitionId':this.currentCompetationId }

				Tournament.getStanding(tournamentData).then(
				(response)=> {
					if(response.data.status_code == 200) {
						this.standingData = response.data.data
						// here we add extra Field Fot Not Displat Location
					}
				},
				(error) => {
					alert('Error in Getting Standing Data')
				}
			)
			}

		},
		changeTeam(Id, Name) {
			// here we dispatch Method

			this.$store.dispatch('setCurrentScheduleView','teamDetails')
			this.$root.$emit('changeComp', Id, Name);
		},
	}

}
</script>

<template>
<div>

<table class="table table-hover table-bordered" v-if="standingData.length > 0">
	<thead>

		<th></th>
		<th>Played</th>
		<th>Won</th>
		<th>Draws</th>
		<th>Lost</th>
		<th>Goals for</th>
		<th>Goals against</th>
    <th>Goal difference</th>
    <th>Points</th>
	</thead>
	<tbody>
		<tr v-for="stand in standingData">

			<td align="left">
				<a href="" @click.prevent="changeTeam(stand.team_id, stand.name)">
					 <!--<img :src="stand.teamFlag" width="20">-->
           <span :class="'flag-icon flag-icon-'+stand.teamCountryFlag"></span>
					<span>
					{{stand.name}}
					</span>
				</a>
			</td>
			<td class="text-center">{{stand.played}}</td>
			<td class="text-center">{{stand.won}}</td>
			<td class="text-center">{{stand.draws}}</td>
			<td class="text-center">{{stand.lost}}</td>
			<td class="text-center">{{stand.goal_for}}</td>
			<td class="text-center">{{stand.goal_against}}</td>
      <td class="text-center">{{stand.goal_for - stand.goal_against}}</td>
      <td class="text-center">{{stand.points}}</td>
		</tr>
	</tbody>
</table>
<span v-if="standingData.length == 0 && drawType != 'Elimination' ">No information available</span>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'

export default {
	props: ['currentCompetationId','drawType'],
	data() {
		return {
			standingData:[],
      currentLCompetationId: this.currentCompetationId
		}
	},
  created: function() {
     this.$root.$on('setStandingData', this.getData);
  },
	mounted() {
		// here we call function to get all the Draws Listing
		this.getData(this.currentLCompetationId)
	},
	methods: {
		getData(currentLCompetationId) {

			if(currentLCompetationId != 0) {

				let TournamentId = this.$store.state.Tournament.tournamentId
				let tournamentData = {'tournamentId': TournamentId,
			'competitionId':currentLCompetationId }

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

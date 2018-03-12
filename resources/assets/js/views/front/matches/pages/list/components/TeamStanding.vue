<template>
	<div>
		<table class="mt-3 table table-hover table-bordered mt-3" v-if="standingData.length > 0">
			<thead>
				<th class="text-center"></th>
				<th class="text-center">{{ $lang.team_played_label }}</th>
				<th class="text-center">{{ $lang.team_won_label }}</th>
				<th class="text-center">{{ $lang.team_draws_label }}</th>
				<th class="text-center">{{ $lang.team_lost_label }}</th>
				<th class="text-center">{{ $lang.team_for_label }}</th>
				<th class="text-center">{{ $lang.team_against_label }}</th>
		    <th class="text-center">{{ $lang.team_difference_label }}</th>
		    <th class="text-center">{{ $lang.team_points_label }}</th>
			</thead>
			<tbody>
				<tr v-for="standing in standingData">
					<td align="left">
		        <span :class="'flag-icon flag-icon-' + standing.teamCountryFlag"></span>
						<span>{{ standing.name }}</span>
					</td>
					<td class="text-center">{{ standing.played }}</td>
					<td class="text-center">{{ standing.won }}</td>
					<td class="text-center">{{ standing.draws }}</td>
					<td class="text-center">{{ standing.lost }}</td>
					<td class="text-center">{{ standing.goal_for }}</td>
					<td class="text-center">{{ standing.goal_against }}</td>
		      <td class="text-center">{{ standing.goal_for - standing.goal_against | formatGoals }}</td>
		      <td class="text-center">{{ standing.points }}</td>
				</tr>
			</tbody>
		</table>
		<span v-if="standingData.length == 0 && competitionType != 'Elimination'">No information available</span>
	</div>
</template>

<script type="text/babel">
	import MatchList from '../../../../../../api/matchlist.js';

	export default {
		props: ['currentCompetitionId', 'competitionType'],
		data() {
			return {
				standingData: [],
			};
		},
		filters: {
			formatGoals: function(goal) {
	      (goal > 0) ? '+' + goal : goal;
	    }
		},
		created() {
			this.$root.$on('setStandingData', this.getStandingData);
		},
		mounted() {
			this.getStandingData(this.currentCompetitionId);
		},
		methods: {
			getStandingData(currentCompetitionId) {
				if(currentCompetitionId != 0) {
					let tournamentId = tournamentData.id;
					let data = {'tournamentId': tournamentId, 'competitionId': currentCompetitionId};

					MatchList.getStanding(data).then(
						(response)=> {
							if(response.data.status_code == 200) {
								this.standingData = response.data.data;
							}
						},
						(error) => {
							alert('Error in getting standing data');
						}
					);
				}
			},
		},
	};
</script>
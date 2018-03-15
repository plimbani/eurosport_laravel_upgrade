<template>
	<div>
		<table class="table table-hover table-bordered" v-if="standingData.length > 0">
			<thead>
				<th class="text-center"></th>
				<th class="text-center">{{ $t('matches.played') }}</th>
				<th class="text-center">{{ $t('matches.won') }}</th>
				<th class="text-center">{{ $t('matches.draws') }}</th>
				<th class="text-center">{{ $t('matches.lost') }}</th>
				<th class="text-center">{{ $t('matches.for') }}</th>
				<th class="text-center">{{ $t('matches.against') }}</th>
		    <th class="text-center">{{ $t('matches.difference') }}</th>
		    <th class="text-center">{{ $t('matches.points') }}</th>
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
		<span v-if="standingData.length == 0 && competitionType != 'Elimination'">{{ $t('matches.no_information_available') }}</span>
	</div>
</template>

<script type="text/babel">
	import MatchList from '../../../../../../api/frontend/matchlist.js';

	export default {
		props: ['currentCompetitionId', 'competitionType'],
		data() {
			return {
				standingData: [],
			};
		},
		filters: {
			formatGoals: function(goal) {
	      return (goal > 0) ? ('+' + goal) : goal;
	    }
		},
		created() {
			this.$root.$on('setStandingData', this.getStandingData);
		},
		beforeDestroy() {
			this.$root.$off('setStandingData');
		},
		mounted() {
			this.getStandingData(this.currentCompetitionId);
		},
		methods: {
			getStandingData(currentCompetitionId) {
				if(currentCompetitionId != 0) {
					let tournamentId = tournamentData.id;
					let data = {'tournamentId': tournamentId, 'competitionId': currentCompetitionId};

					MatchList.refreshStanding(data).then(
						(response)=> {
							if(response.data.status_code == 200) {
								this.standingData = response.data.data;
							}
						},
						(error) => {
							console.log('Error in getting standing data');
						}
					);
				}
			},
		},
	};
</script>

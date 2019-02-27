<template>
	<div>
		<div class="table-responsive">
			<table class="table" v-if="standingData.length > 0">
				<thead class="no-border">
					<tr>
						<th></th>
						<th class="text-center" scope="col">{{ $t('matches.played') }}</th>
						<th class="text-center" scope="col">{{ $t('matches.won') }}</th>
						<th class="text-center" scope="col">{{ $t('matches.draws') }}</th>
						<th class="text-center" scope="col">{{ $t('matches.lost') }}</th>
						<th class="text-center" scope="col">{{ $t('matches.for') }}</th>
						<th class="text-center" scope="col">{{ $t('matches.against') }}</th>
				    	<th class="text-center" scope="col">{{ $t('matches.difference') }}</th>
				    	<th class="text-center" scope="col">{{ $t('matches.points') }}</th>
				    </tr>
				</thead>
				<tbody>
					<tr v-for="standing in standingData">
						<td align="left">
							<div class="matchteam-details">
			  				<span :class="'matchteam-flag flag-icon flag-icon-'+standing.teamCountryFlag"></span>
			  				<div class="matchteam-dress" v-if="standing.shorts_color && standing.shirt_color">
			            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="standing.shorts_color" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="standing.shirt_color" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
			          </div>
								<span class="matchteam-name">{{standing.name}}</span>
							</div>
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
		</div>
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
		beforeCreate() {
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
						}
					);
				}
			},
		},
	};
</script>

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
			            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><g><polygon v-bind:fill="standing.shorts_color" points="13.2 40 13.2 62 30.2 62 32.2 56 34.2 62 51.2 62 51.2 40 13.2 40"/></g><path v-bind:fill="standing.shirt_color" d="M63.81,10.81,51.2,0h-13a6.5,6.5,0,0,1-6,4,6.5,6.5,0,0,1-6-4h-13L.59,10.81A1.7,1.7,0,0,0,.5,13.3L7.2,20l6-4V40h38V16l6,4,6.7-6.7A1.7,1.7,0,0,0,63.81,10.81Z"/></g></svg>
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

<template>
	<div>
		<div v-if="showView == 'teams'">
			<div class="table-responsive">
				<table id="teamsTable" class="table table-hover table-bordered">
					<thead class="no-border">
						<tr>
							<th>{{ $t('matches.team') }}</th>
							<th>{{ $t('matches.categories') }}</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="team in paginated('teams')">
							<td>
								<div class="matchteam-details">
			      			<span :class="'matchteam-flag flag-icon flag-icon-'+team.countryFlag"></span>
									<div class="matchteam-dress" v-if="team.shorts_color && team.shirt_color">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="team.shorts_color" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="team.shirt_color" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
									</div>
									<span class="text-center matchteam-name"><a class="text-primary" href="javascript:void(0)" @click.prevent="getTeamDetails(team.id, team.name)">{{team.name}}</a></span>
								</div>
							</td>
							<td>
								<a href="javascript:void(0)" @click.prevent="showCompetitionDetail(team)" class="text-primary pull-left text-left">
								<u>{{team.competationName}}</u></a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="no-data h6 text-muted" v-if="teams.length == 0">{{ $t('matches.no_teams_found') }}</div>
			<paginate name="teams" :list="teams" ref="paginator" :per="noOfRecords" class="paginate-list"></paginate>
			
			<div>
		        <div class="match-pagination-list" v-if="teams.length > 0">
		          <paginate-links for="teams" :show-step-links="true" :limit="2" :async="true"></paginate-links>
		        </div>
	      	</div>
	    </div>
		
		<!-- Competition detail page -->
      	<competition v-if="showView == 'competition'" :matches="matches" :competitionDetail="competitionDetail" :currentView="'Competition'" :fromView="'Teams'" :categoryId="currentCategoryId"></competition>

      	<!-- Team detail page -->
      	<team-details v-if="showView == 'teamdetail'" :teamMatches="teamMatches" :currentView="'TeamDetail'" :currentSelectedTeamName="currentSelectedTeamName" :fromView="'Teams'"></team-details>
	</div>
</template>

<script type="text/babel">
  import VuePaginate from 'vue-paginate';
  import TeamList from '../../../../../api/frontend/teamlist.js';
  import MatchList from '../../../../../api/frontend/matchlist.js';
  import Competition from './../list/components/Competition.vue';
  import TeamDetails from './components/TeamDetails.vue';

  export default {
  	data() {
      return {
        teams: [],
        matches: [],
        showView: 'teams',
        tournamentData: tournamentData,
        paginate: ['teams'],
        noOfRecords: 20,
        recordCounts: [5, 10, 20, 50, 100],
        competitionDetail: {
          id: '',
          name: '',
          type: '',
        },
        currentCategoryId: '',
        teamMatches: [],
        currentSelectedTeamName: '',
        competitionFixtures: [],
      };
    },
    computed: {
    },
    components: {
    	Competition,
    	TeamDetails,
    },
    mounted() {
    	this.getAllTournamentTeams();
    },
    created() {
    	this.$root.$on('showCompetitionViewFromTeam', this.showCompetitionViewFromTeam);
    	this.$root.$on('showTeamsList', this.showTeamsList);
    },
    beforeCreate() {
      // Remove custom event listener
      this.$root.$off('showCompetitionViewFromTeam');
      this.$root.$off('showTeamsList');
    },
    methods: {
	    getAllTournamentTeams() {
	      let data = {'tournamentId': tournamentData.id};
	      TeamList.getTournamentTeams(data).then(
	        (response)=> {
	          if(response.data.status_code == 200) {
	            this.teams = _.orderBy(response.data.data, ['name'], ['asc']);
	            this.competitionFixtures = response.data.competitionFixtures;
	            let vm = this;
	            if(vm.competitionFixtures) {
	              vm.teams = _.filter(vm.teams, function(o) {
	                return vm.competitionFixtures[o.competationId] > 0;
	              });
	            }
	          }
	        },
	        (error) => {
	          alert('Error in Getting Draws')
	        }
	      )
	    },
	    getSelectedCompetitionDetails(competitionId, competitionName, competitionType) {
	        var tournamentId = tournamentData.id;
	        var data = {'tournamentId': tournamentId, 'competitionId': competitionId};
	        var vm = this;

	        this.competitionDetail.name = competitionName;
	        this.competitionDetail.id = competitionId;
	        this.competitionDetail.type = competitionType;

	        MatchList.getFixtures(data).then(
	          (response)=> {
	            if(response.data.status_code == 200) {
	              vm.matches = response.data.data;
	              vm.$root.$emit('setMatchesForMatchList', _.cloneDeep(response.data.data));
	            }
	          },
	          (error) => {
	          }
	        )
	    },
	    showCompetitionDetail(team) {
	    	this.showView = 'competition';
	        var id = team.competationId;
	        var competitionName = team.competationName;
	        var competitionType = team.competation_type;
	        this.currentCategoryId = team.age_group_id;
	        this.getSelectedCompetitionDetails(id, competitionName, competitionType);
	    },
	    showCompetitionViewFromTeam(id, competitionName, competitionType) {
	        this.showView = 'competition';
	        this.getSelectedCompetitionDetails(id, competitionName, competitionType);
	    },
	    showTeamsList() {
	    	this.showView = 'teams';
        	this.getAllTournamentTeams();
	    },
	    changeTeam(id, name) {
			this.getTeamDetails(id, name);
		},
		getTeamDetails(teamId, teamName) {
			let TournamentId = this.tournamentData.id;
			let tournamentData = {'tournamentId': TournamentId, 'teamId': teamId,'is_scheduled': 1};
			this.currentSelectedTeamName = teamName;
			this.showView = 'teamdetail';
			let vm = this;
			MatchList.getFixtures(tournamentData).then(
				(response)=> {
					if(response.data.status_code == 200) {
						vm.teamMatches = response.data.data;
			            vm.teamMatches = _.orderBy(vm.teamMatches, ['match_datetime'], ['asc']);
			            vm.$root.$emit('setMatchesForMatchList', _.cloneDeep(response.data.data));
					}
				},
				(error) => {
				  alert('Error in Getting Draws')
				}
			)
	    },
    }
  }
  </script>
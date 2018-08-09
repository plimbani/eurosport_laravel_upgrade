<template>
	<div>
		<div v-if="showView == 'teams'">
			<div class="table-responsive">
				<table id="teamsTable" class="table table-hover table-bordered">
					<thead>
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
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><g><polygon v-bind:fill="team.shorts_color" points="13.2 40 13.2 62 30.2 62 32.2 56 34.2 62 51.2 62 51.2 40 13.2 40"/></g><path v-bind:fill="team.shirt_color" d="M63.81,10.81,51.2,0h-13a6.5,6.5,0,0,1-6,4,6.5,6.5,0,0,1-6-4h-13L.59,10.81A1.7,1.7,0,0,0,.5,13.3L7.2,20l6-4V40h38V16l6,4,6.7-6.7A1.7,1.7,0,0,0,63.81,10.81Z"/></g></svg>
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
	            this.teams = response.data.data
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
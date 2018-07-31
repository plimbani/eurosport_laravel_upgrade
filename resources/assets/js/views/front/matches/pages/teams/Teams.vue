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
			          			<span :class="'flag-icon flag-icon-'+team.countryFlag"></span>
								<span class="text-center"><a @click.prevent="changeTeam(team.id, team.name)" class="text-primary" href="javascript:void(0)">{{team.name}}</a></span>
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
	</div>
</template>

<script type="text/babel">
  import VuePaginate from 'vue-paginate';
  import TeamList from '../../../../../api/frontend/teamlist.js';
  import MatchList from '../../../../../api/frontend/matchlist.js';
  import Competition from './../list/components/Competition.vue';

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
      };
    },
    computed: {
    },
    components: {
    	Competition,
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
			let TournamentId = tournamentData.id;
			let tournamentData = {'tournamentId': TournamentId, 'teamId': teamId,'is_scheduled': 1};
			this.otherData.TeamName = teamName;
			Tournament.getFixtures(tournamentData).then(
			(response)=> {
			  if(response.data.status_code == 200) {
			    this.matchData = response.data.data
			    // here we add extra Field Fot Not Displat Location
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
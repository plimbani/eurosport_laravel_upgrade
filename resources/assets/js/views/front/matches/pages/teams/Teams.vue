<template>
	<div>
		<div class="table-responsive">
			<table id="teamsTable" class="table table-hover table-bordered mt-4">
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
							<span class="text-center"><a class="text-primary" href="">{{team.name}}</a></span>
						</td>
						<td>
							<a href="" class="text-primary pull-left text-left">
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
</template>

<script type="text/babel">
  import VuePaginate from 'vue-paginate';
  import TeamList from '../../../../../api/frontend/teamlist.js';

  export default {
  	data() {
      return {
        teams: [],
        tournamentData: tournamentData,
        paginate: ['teams'],
        noOfRecords: 20,
        recordCounts: [5, 10, 20, 50, 100],
      };
    },
    computed: {
    },
    components: {
    },
    mounted() {
    	this.getAllTournamentTeams();
    },
    created() {
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
	    }
    }
  }
  </script>
<template>
	<div class="row">
		<div class="col-sm-12">
			<div class="card main-card">
				<div class="card-block">
					<div class="row">
						<div class="col-lg-12">
							<div class="tabs tabs-primary user_tabs">
								<ul class="nav nav-tabs" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab"
										href="javascript:void(0)" role="tab"><div class="wrapper-tab">{{$lang.duplicate_tournament}}</div></a>
									</li>
								</ul>
								<DuplicateTournamentList :tournamentList="tournamentList"></DuplicateTournamentList>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script type="text/babel">
import Tournament from '../../../api/tournament.js'
import DuplicateTournamentList from '../../admin/tournaments/DuplicateTournamentList.vue'
export default {
	data() {
		return {
			'header' : 'header',
			'tournamentList': {
				'tournamentData': [],
				'tournamentDataCount': 0,
      		}
		}
	},
	components: {
		DuplicateTournamentList
	},
	created() {
		this.getAllTournaments();
		this.$root.$on('setSearch', this.getAllTournaments);
	    this.$root.$on('clearSearch', this.clearSearch);
	    this.$root.$on('displayUpdatedTournaments', this.clearSearch);	    
	},
	methods: {
		clearSearch() {
		    this.getAllTournaments()
		},
	    getAllTournaments(tournamentNameSearch='') {
	    	let tournamentData = {}
			if(tournamentNameSearch != '') {
			  	tournamentData.tournamentNameSearch = tournamentNameSearch;
			}
            Tournament.duplicateTournamentList(tournamentData).then(
              (response) => {
                this.tournamentList.tournamentData = response.data.data;
                this.tournamentList.tournamentDataCount = response.data.data.length;
              },
              (error) => {
              }
            )            
        }
	}
}
</script>

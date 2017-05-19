<template>
	<div class="tab-content">
		<div class="card">
			<div class="card-block pb-0 ">
          <div class="row align-items-center justify-content-start">
            <div class="col-3 align-self-center">
              <h6 class="m-0"><strong>{{$lang.pitch_planner_label}}</strong></h6>
            </div>
            <div class="col-9 align-self-center">
              <tournamentFilter :section="section"></tournamentFilter>
            </div>
          </div>
	  			<div class="mt-4">
	  				<pitch-planner-table></pitch-planner-table>
	  			</div>
			</div>
		</div>
	</div>
</template>

<script type="text/babel">
var moment = require('moment');
	import PitchModal from '../../../components/PitchModal.vue';
	import PitchPlannerTable from '../../../components/PitchPlannerTable.vue';
  import TournamentFilter from '../../../components/TournamentFilter.vue';

	export default {
    data() {
       return {
         'tournamentId': this.$store.state.Tournament.tournamentId,
         'section':'pitchPlanner'
       }
    },
    created: function() {
      this.$root.$on('getTeamsByTournamentFilter', this.setFilter);
    },
    methods: {
      setFilter(filterKey,filterValue) {
        this.tournamentFilter.filterKey = filterKey
        this.getFixturesFilter(filterKey,filterValue)
      },
      getFixturesFilter(filterKey,filterValue) {
        let teamData = {'tournamentId':this.tournament_id,'filterKey':filterKey, 'filterValue': filterValue};
        // console.log(teamData,'td')
        Tournament.getTeams(teamData).then(
          (response) => {
            this.teams = response.data.data
          },
          (error) => {
             console.log('Error occured during Tournament api ', error)
          }
        )
      }
    },
    mounted() {
      let vm = this
    	this.$store.dispatch('SetPitches',this.tournamentId);
    	// Here we put validation check
    let tournamentId = this.$store.state.Tournament.tournamentId
    if(tournamentId == null || tournamentId == '' || tournamentId == undefined) {
      toastr['error']('Please Select Tournament', 'Error');
      this.$router.push({name: 'welcome'});
    } else {
      // Means Set Here
     let currentNavigationData = {activeTab:'pitch_planner',
     currentPage: 'Pitch Planner'}
      this.$store.dispatch('setActiveTab', currentNavigationData)
    }

    },
    components: {
        PitchModal, PitchPlannerTable, TournamentFilter
    }
}
</script>

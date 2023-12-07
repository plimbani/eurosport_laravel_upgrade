<template>
<div>

<table class="mt-3 table table-hover table-bordered mt-3 tbl-standing" v-if="standingData.length > 0">
	<thead>

		<th class="text-center"></th>
		<th class="text-center" width="10%">{{$lang.team_played_label}}</th>
		<th class="text-center" width="10%">{{$lang.team_won_label}}</th>
		<th class="text-center" width="10%">{{$lang.team_draws_label}}</th>
		<th class="text-center" width="10%">{{$lang.team_lost_label}}</th>
		<th class="text-center" width="10%">{{$lang.team_for_label}}</th>
		<th class="text-center" width="10%">{{$lang.team_against_label}}</th>
    <th class="text-center" width="10%">{{$lang.team_difference_label}}</th>
    <th class="text-center" width="10%">{{$lang.team_points_label}}</th>
	</thead>
	<tbody>
		<tr v-for="stand in standingData">

			<td align="left">
				<div class="matchteam-details">
  				<span v-if="stand.teamCountryFlag != null" :class="'matchteam-flag flag-icon flag-icon-'+stand.teamCountryFlag"></span>
  				<div class="matchteam-dress" v-if="stand.shorts_color && stand.shirt_color">
            		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.4 62"><g><polygon class="cls-1" v-bind:fill="stand.shorts_color" points="13.79 39.72 13.79 61.04 30.26 61.04 32.2 55.22 34.14 61.04 50.61 61.04 50.61 39.72 13.79 39.72"/></g><path class="cls-2" v-bind:fill="stand.shirt_color" d="M62.83,11.44,50.61,1H38A6.29,6.29,0,0,1,32.2,4.84,6.29,6.29,0,0,1,26.39,1H13.79L1.57,11.44a1.65,1.65,0,0,0-.09,2.41L8,20.34l5.81-3.87V39.72H50.61V16.47l5.81,3.87,6.5-6.49A1.65,1.65,0,0,0,62.83,11.44Z"/></svg>
          		</div>
					<span class="text-center matchteam-name">
						<a class="text-primary" href="" @click.prevent="changeTeam(stand.id, stand.name)">{{stand.name}}</a>
					</span>
				</div>
				<!-- <a href="" @click.prevent="changeTeam(stand.team_id, stand.name)"> -->
					 <!--<img :src="stand.teamFlag" width="20">-->
				<!-- </a> -->
			</td>
			<td class="text-center" width="10%">{{stand.played}}</td>
			<td class="text-center" width="10%" >{{stand.won}}</td>
			<td class="text-center" width="10%">{{stand.draws}}</td>
			<td class="text-center" width="10%">{{stand.lost}}</td>
			<td class="text-center" width="10%">{{stand.goal_for}}</td>
			<td class="text-center" width="10%">{{stand.goal_against}}</td>
      <td class="text-center" width="10%">{{stand.goal_for - stand.goal_against | formatGD}}</td>
      <td class="text-center" width="10%">{{stand.points}}</td>
		</tr>
	</tbody>
</table>
<span v-if="standingData.length == 0 && drawType != 'Elimination' ">No information available
<div class="mt-2"></div>
</span>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'

export default {
	props: ['currentCompetationId','drawType'],
	data() {
		return {
			standingData:[],
      currentLCompetationId: this.currentCompetationId
		}
	},

  filters: {
    formatGD: function(val) {
       let gdVal = val
       if(gdVal > 0)
          return '+'+gdVal
       return gdVal
    }
  },
  created: function() {
    this.$root.$on('setStandingData', this.getData);
    this.$root.$on('getcurrentCompetitionStanding', this.getcurrentCompetitionStanding);
  },
  beforeCreate: function() {
  	// Remove custom event listener
		this.$root.$off('setStandingData');
		this.$root.$off('getcurrentCompetitionStanding');
  },
	mounted() {
		// here we call function to get all the Draws Listing
		this.getData(this.currentLCompetationId)
	},
	methods: {
		getData(currentLCompetationId) {

			if(currentLCompetationId != 0) {

				let TournamentId = this.$store.state.Tournament.tournamentId
				let tournamentData = {'tournamentId': TournamentId,
			'competitionId':currentLCompetationId }

				Tournament.getStanding(tournamentData).then(
				(response)=> {
					if(response.data.status_code == 200) {
						this.standingData = response.data.data
						// here we add extra Field Fot Not Displat Location
					}
				},
				(error) => {
					alert('Error in Getting Standing Data')
				}
			)
			}

		},
		getcurrentCompetitionStanding(currentLCompetationId) {
			this.standingData = [];
			$("body .js-loader").removeClass('d-none');
          	if(currentLCompetationId != 0) {
	          let tournamentData = {'tournamentId': this.$store.state.Tournament.tournamentId,'competitionId': currentLCompetationId}
	          Tournament.refreshStanding(tournamentData).then(
	            (response)=> {
	            	$("body .js-loader").addClass('d-none');
		            if(response.data.status_code == 200){
		            	this.standingData = response.data.data
		            }
	            },
	           )
	       }
        },
		changeTeam(Id, Name) {
			// here we dispatch Method
			window.changeTeamId = Id;
      		window.changeTeamname = Name;
      		
			this.$store.dispatch('setCurrentScheduleView','teamDetails')
			this.$root.$emit('changeComp', Id, Name);
		},
	}

}
</script>

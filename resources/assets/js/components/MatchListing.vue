<template>
  <div>
  	<div v-if="currentScheduleView == 'matchList'" class="form-group row d-flex flex-row align-items-center">
    	<label class="col-sm-2"><h6 class="mb-0">Match overview</h6></label>
    	<div class="col-sm-10">
		    <select class="form-control ls-select2 col-sm-4"
		    v-on:change="onChangeMatchDate"
			v-model="matchDate">
			<option v-for="option in tournamentDates"
      v-bind:value="option"
			>{{option | formatDate}}
			</option>
			</select>
		</div>
	</div>
    <component :is="currentScheduleView"
    :matchData="matchData" :otherData="otherData"
    > </component>
  </div>
</template>
<script type="text/babel">

import Tournament from '../api/tournament.js'
import TeamDetails from './TeamDetails.vue'
import TeamList from './TeamList.vue'
import MatchList from './MatchList.vue'
import DrawDetails from './DrawDetails.vue'
import DrawsListing from './DrawsListing.vue'
import LocationList from'./LocationList.vue'
import DrawList from './DrawList.vue'

var moment = require('moment')

export default {
	data() {
		return {
			matchData: [],otherData:[],matchDate:this.$store.state.Tournament.tournamentStartDate,tournamentDates:[],
			currentComponent: this.$store.state.currentScheduleView
		}
	},
  filters: {
    formatDate: function(date) {
      // return moment(date).format("ddd DD/MM/YYYY h:mm");
      let SDate = moment(date,'DD/MM/YYYY')
      return moment(SDate).format("DD MMM YYYY");
    }
  },
	mounted() {
	  // First Called match Listing Data and then passed

 	  // here we call by Default Match Listing Function to display matchlist
 	  let tournamentStartDate = this.$store.state.Tournament.tournamentStartDate
 	  let tournamentEndDate = this.$store.state.Tournament.tournamentEndDate
    this.tournamentDates = this.getDateRange(tournamentStartDate,tournamentEndDate,'mm/dd/yyyy')
	  this.$store.dispatch('setCurrentScheduleView','matchList')
	  // By Default Set for ot Todays Date
	  this.getAllMatches(tournamentStartDate)
	},
	created: function() {
       this.$root.$on('changeComp', this.setMatchData);
  	},
	computed: {
		currentScheduleView() {
			return this.$store.state.currentScheduleView
		}
	},
	components: {
		MatchList,TeamDetails,LocationList,DrawsListing,
		DrawDetails,TeamList,DrawList
	},
	methods: {
		onChangeMatchDate(){
			let matchDate = this.matchDate
			this.getAllMatches(matchDate)
		},
		getDateRange(startDate, stopDate, dateFormat)
		{

          var dateArray = [];

          var currentDate = new Date(moment(startDate, 'DD/MM/YYYY'))
          var stopDate = new Date(moment(stopDate,'DD/MM/YYYY'))

          while (currentDate <= stopDate) {
              dateArray.push( moment(currentDate).format('DD/MM/YYYY') )
              currentDate = moment(currentDate).add(1, 'days');
          }
        return dateArray;

			  /*var dateArray = []
		    var currentDate = moment(startDate).format('MM/DD/YYYY');
        alert(currentDate)
		    var stopDate = moment(endDate).format('MM/DD/YYYY');
        alert('helo')
        alert(currentDate)
		    while (currentDate <= stopDate) {
		        dateArray.push( moment(currentDate).format('MM/DD/YYYY') )
		        currentDate = moment(currentDate).add(1, 'days');
		    }

		    return dateArray;*/
		},
		setMatchData(id, Name='') {

			let comp = this.$store.state.currentScheduleView

			if(comp == 'locationList') {
				// Now here we call Function get all match for location
				this.getAllMatchesLocation(id)
			}
			if(comp == 'teamDetails') {
				this.getTeamDetails(id, Name)
			}
			if(comp == 'drawDetails') {
				this.getDrawDetails(id, Name)
			}
      if(comp == 'matchList') {
        this.getDrawDetails(id, Name)
      }
		},
		getDrawDetails(drawId, drawName) {
			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = {'tournamentId': TournamentId,
			'competitionId':drawId,'is_scheduled':1}

			this.otherData.DrawName = drawName
			this.otherData.DrawId = drawId
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

			// Also Called Standings Data


		},
		getTeamDetails(teamId, teamName) {
			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = {'tournamentId': TournamentId,
			'teamId':teamId,'is_scheduled':1}
			this.otherData.TeamName = teamName
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
		getAllMatchesLocation(fixtureData){

			let TournamentId = this.$store.state.Tournament.tournamentId
			let PitchId = fixtureData.pitchId
			let tournamentData = {'tournamentId': TournamentId, 'pitchId':PitchId,'is_scheduled':1}
			this.otherData.Name = fixtureData.venue_name+'-'+fixtureData.pitch_number

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
		getAllMatches(date) {
			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = {'tournamentId': TournamentId,
			'tournamentDate':date,'is_scheduled':1}
			Tournament.getFixtures(tournamentData).then(
				(response)=> {
					if(response.data.status_code == 200) {
						this.matchData = response.data.data
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

<template>
  <div>
    <component :is="currentScheduleView" 
    :matchData="matchData" :otherData="otherData"
    > </component>
  </div>
</template>
<script type="text/babel">

import MatchList from './MatchList.vue'
import DrawDetails from './DrawDetails.vue'
import TeamDetails from './TeamDetails.vue'
import TeamList from './TeamList.vue'

import LocationList from'./LocationList.vue'
import DrawsListing from './DrawsListing.vue'

import Tournament from '../api/tournament.js'

export default {
	data() {
		return {
			matchData: [],otherData:[]
		}
	},
	mounted() {
	  // First Called match Listing Data and then passed
	  
 	  // here we call by Default Match Listing Function to display matchlist
	  this.$store.dispatch('setCurrentScheduleView','matchList')
	  this.getAllMatches()
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
		MatchList,TeamDetails,LocationList,DrawsListing,DrawDetails,TeamList
	},
	methods: {
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
		},
		getDrawDetails(drawId, drawName) {
			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = {'tournamentId': TournamentId, 
			'competitionId':drawId}
			
			this.otherData.DrawName = drawName

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
		getTeamDetails(teamId, teamName) {
			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = {'tournamentId': TournamentId, 
			'teamId':teamId}
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
		getAllMatchesLocation(locationId){
			alert('calledLoc')
			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = {'tournamentId': TournamentId, 'pitchId':locationId}
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
		getAllMatches() {

			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = {'tournamentId': TournamentId}			
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
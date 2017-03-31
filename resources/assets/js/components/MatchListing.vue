<template>
  <div>
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
		MatchList,TeamDetails,LocationList,DrawsListing,
		DrawDetails,TeamList,DrawList
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

			// Also Called Standings Data
			
			
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
		getAllMatchesLocation(fixtureData){
			
			let TournamentId = this.$store.state.Tournament.tournamentId
			let PitchId = fixtureData.pitchId
			let tournamentData = {'tournamentId': TournamentId, 'pitchId':PitchId}
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
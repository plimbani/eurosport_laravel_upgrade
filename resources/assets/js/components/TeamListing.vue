<template>
<div>
   <!-- <component :is="currentScheduleView" :matchData="matchData"></component>-->
   <component :is="currentScheduleView" :matchData="matchData" 
   :otherData="otherData"></component>
</div>

</template>
<script type="text/babel">

import Tournament from '../api/tournament.js'
import TeamDetails from './TeamDetails.vue'
import TeamList from './TeamList.vue'
import MatchList from './MatchList.vue'
import DrawDetails from './DrawDetails.vue'
import DrawsListing from './DrawsListing.vue'
import LocationList from './LocationList.vue'
import MatchListing from './MatchListing.vue'

export default {
	data() {
		return {
			matchData:[], otherData:[]
		}
	},
	mounted() {
		// here we call function to get all the Draws Listing
		this.$store.dispatch('setCurrentScheduleView','teamList')
		this.getAllTournamentTeams()
	},
	components: {
		TeamDetails,DrawsListing,TeamList,MatchList,DrawDetails,MatchListing,LocationList
	},
	created: function() {
       this.$root.$on('changeComp', this.setMatchData); 
  	},
	computed: {
		currentScheduleView() {
			return this.$store.state.currentScheduleView
		}
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
		getDrawDetails(drawId, drawName) {

			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData = {'tournamentId': TournamentId, 
			'competitionId':drawId}
			
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
		teamDetails() {
			//this.$store.dispatch('setCurrentScheduleView','teamDetails')
		},
		getAllTournamentTeams() {
			let TournamentId = this.$store.state.Tournament.tournamentId
			let tournamentData={'tournamentId':TournamentId}
			
			Tournament.getTournamentTeams(tournamentData).then(
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
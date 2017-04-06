<template>
<div>

<component :is="currentScheduleView" :matchData="matchData" 
   :otherData="otherData"></component>
</div>
</template>
<script type="text/babel">
import Tournament from '../api/tournament.js'
import MatchListing from './MatchListing.vue'
import DrawList from './DrawList.vue'
import MatchList from './MatchList.vue'
import DrawDetails from './DrawDetails.vue'
import LocationList from './LocationList.vue'
import TeamDetails from './TeamDetails.vue'

export default {
	data() {
		return {
			drawsData:[],
			matchData:[], otherData:[],
			drawsList: [],draw:''
		}
	},
	computed: {
		currentScheduleView() {
			return this.$store.state.currentScheduleView
		}
	},
	mounted() {
		// here we call function to get all the Draws Listing
		this.$store.dispatch('setCurrentScheduleView','drawList')
		this.getAllDraws()
	},
	components: {
		MatchListing,DrawList,MatchList,DrawDetails,LocationList,TeamDetails
	},
	created: function() {
       this.$root.$on('changeDrawListComp', this.setMatchData); 
  	},
	methods: {
		onChangeDraw() {
			alert(this.draw)
		},
		setMatchData(id, Name='') {
			
			let comp = this.$store.state.currentScheduleView
			
			if(comp == 'locationList') {
				// Now here we call Function get all match for location
				this.getAllMatchesLocation(id)
			} 
			if(comp == 'teamDetails') {
				alert('called')
				this.getTeamDetails(id, Name)
			}
			if(comp == 'drawDetails') {
				this.getDrawDetails(id, Name)
			}	
		},
		getAllDraws() {
			let TournamentId = this.$store.state.Tournament.tournamentId
			Tournament.getAllDraws(TournamentId).then(
				(response)=> {
					if(response.data.status_code == 200) {
						this.matchData = response.data.data
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
		drawDetails(drawData) {
			console.log(drawData)
		    //this.$store.dispatch('setCurrentScheduleView','matchListing')
			this.$root.$emit('changeComp1', 'matchListing');
		}
	}
}
</script>
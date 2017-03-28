<template>
<div>
   <!-- <component :is="currentScheduleView" :matchData="matchData"></component>-->
   <teamList :matchData="matchData"></teamList>
</div>

</template>
<script type="text/babel">

import Tournament from '../api/tournament.js'
import TeamDetails from './TeamDetails.vue'
import TeamList from './TeamList.vue'

import DrawDetails from './DrawDetails.vue'
import DrawsListing from './DrawsListing.vue'


export default {
	data() {
		return {
			matchData:[],
		}
	},
	mounted() {
		// here we call function to get all the Draws Listing
		this.$store.dispatch('setCurrentScheduleView','teamList')
		this.getAllTournamentTeams()
	},
	components: {
		TeamDetails,DrawsListing,TeamList
	},
	computed: {
		currentScheduleView() {
			return this.$store.state.currentScheduleView
		}
	},
	methods: {
		teamDetails() {
			this.$store.dispatch('setCurrentScheduleView','teamDetails')
		},
		getAllTournamentTeams() {
			let TournamentId = this.$store.state.Tournament.tournamentId
			Tournament.getTeams(TournamentId).then(
				(response)=> {
					if(response.data.status_code == 200) {
						alert(JSON.stringify(response.data))
						this.matchData = response.data.data
						// Hello Teams
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